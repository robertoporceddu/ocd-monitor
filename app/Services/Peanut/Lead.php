<?php

namespace App\Services\Peanut;

use Guzzle\Http\Client as HttpClient;

class Lead extends Peanut
{
    public function get($buyer, $schema_name, $system_cutomer_data_id)
    {
        $http = new HttpClient();

        $http = $http->get(
            $this->url . "/tasks/outbound/$buyer/$schema_name/$system_cutomer_data_id?token=$this->token"
        )->send();

        return json_decode($http->getBody())->payload->data ?? null;
    }

    public function addNote($buyer, $schema_name, $system_cutomer_data_id, $note)
    {
        $http = new HttpClient();

        $data = new \stdClass();
        $data->note = substr($note,0,500);

        $http = $http->post(
            $this->url . "/tasks/outbound/$buyer/$schema_name/$system_cutomer_data_id/note?token=$this->token",
            null,
            [ 'data' => json_encode($data) ]
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

    public function setOutcome($buyer, $schema_name, $system_cutomer_data_id, $id_outcome)
    {
        $http = new HttpClient();

        $data = new \stdClass();
        $data->id_outcome = $id_outcome;

        $http = $http->put(
            $this->url . "/tasks/outbound/$buyer/$schema_name/$system_cutomer_data_id?token=$this->token",
            null,
            [ 'data' => json_encode($data) ]
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

    public function inject($buyer, $schema_name, $lead)
    {
        $http = new HttpClient();

        $newLead = $lead;
        $newLead->id = $lead->ocm_status_id;
        $newLead->nominativo = $lead->customer_data_name_cli;
        $newLead->telefono = $lead->customer_data_phone_cli;
        $newLead->supplier = $lead->supplier_name;
        $newLead->mgm = $lead->mgm ?? null;
        for($i = 1; $i < 20; $i++) {
            $newLead->{'f'.$i} = $lead->{'field_'.$i};
        }

        $http = $http->post(
            $this->url . "/tasks/outbound/$buyer/$schema_name/store/recall?token=$this->token",
            null,
            [ 'data' => json_encode($lead) ]
        )->send();

        if ($response = json_decode($http->getBody())) {
            if($response->payload->inserted === false) {
                throw new \Exception("Peanut CRM: " . json_encode($response->payload->validation), 422);
            }
        }

        return $response ?? null;
    }

    public function copyHistory($other_contacts, $to_contact)
    {
        $other_contacts->reverse()->each(function($other_contact) use ($to_contact) {
            if(
                $other_contact->last_outcome_type == 'OP_INTERESSED' or
                $other_contact->last_outcome_type == 'OP_OK' or 
                preg_match('/^BO_.+/',$other_contact->last_outcome_type)
            )
            {
                $contact = $this->get($other_contact->campaign_buyer, $other_contact->campaign_schema, $other_contact->customer_data_id);

                $this->addNote(
                    $to_contact->campaign_buyer,
                    $to_contact->campaign_schema,
                    $to_contact->customer_data_id,
                    "$other_contact->last_outcome_created_at: <strong>$other_contact->last_outcome_name</strong>&nbsp;" . ($contact->notes[0]->note ?? null)
                );
            }
        });
    }

    public function setKoOtherContacts($contacts, $id_outcome)
    {
        $contacts->each(function($contact) use ($id_outcome) {
            if(
                is_null($contact->last_outcome_type) or
                in_array($contact->last_outcome_type,['OP_INTERESSED','OP_AVAILABLE','OP_RECALL','OP_MGMT'])
            )						
            {
                $this->setOutcome(
                    $contact->campaign_buyer,
                    $contact->campaign_schema,
                    $contact->customer_data_id,
                    $id_outcome
                );
            }
        });
    } 

}
