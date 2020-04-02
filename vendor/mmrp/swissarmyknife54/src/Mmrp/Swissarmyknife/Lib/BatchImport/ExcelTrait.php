<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 12/01/17
 * Time: 10:40
 */

namespace Mmrp\Swissarmyknife\Lib\BatchImport;

use App\Models\File;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ExcelTrait
 * @package App\Lib\BatchImport
 */
trait ExcelTrait {

    /**
     * @var string
     */
    protected $excel_trait_resource = NULL;

    /**
     * Initialize ExcelTrait environment
     */
    public function initExcelTrait()
    {
        $this->trait_resource = 'excel ';
    }

    /**
     * Get request line from excel file
     * @param File $file
     * @param string $rows
     * @return mixed
     */
    public function getRowsFromExcelFile(File $file, $rows = 'all')
    {
        $parameter = json_decode($file->parameters);

        $header = $parameter->header;
        $delimiter = ($parameter->delimiter) ? $parameter->delimiter : ',';

        switch ($delimiter) {
            case '\t':
                config(['excel.csv.delimiter' => "\t" ]);
                break;
            default:
                config(['excel.csv.delimiter' => $delimiter ]);
        }

        $items = Excel::selectSheetsByIndex(0)->load($file->path, function ($reader) use ($rows, $header) {
            if(!$header) {
                $reader->noHeading();
            }

            if($rows == 'all'){
                $reader->all();
            } else {
                $reader->limit($rows);
            }

        })->get();

        return $items;
    }

    /**
     * Map the contents of the uploaded file with the destination table
     * @param File $file
     * @param $mapping
     * @param $batch_import_id
     * @return array
     */
    public function prepareInsertsFromExcelFile(File $file, $mapping, $batch_import_id)
    {
        $rows = $this->getRowsFromExcelFile($file,'all');

        $inputs = [];
        foreach ($rows as $row){
            $input_row = [];
            foreach ($mapping as $table_field => $value){
                $input_row[$table_field] = '';
                $csv_fields = explode(',',str_replace(' ','',$value));

                foreach ($csv_fields as $csv_field){
                    $input_row[$table_field] .= $row->$csv_field . ' ';
                }

                $input_row[$table_field] = substr($input_row[$table_field],0, strlen($input_row[$table_field])-1);
            }
            $input_row['batch_import_id'] = $batch_import_id;

            if(isset($input_row['id'])){
                $input_row['id'] = intval($input_row['id']);
            }

            $inputs[] = $input_row;
        }

        return $inputs;
    }
}