<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 12:21
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;


use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Lib\Log;

trait DownloadTrait
{
    /**
     * Used to enable/disable download() method
     * @var bool
     */
    protected $download = TRUE;

    /**
     * Donwload request file
     * @param Request $request
     * @param null $path
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request, $path = NULL)
    {
        if(!$this->index){
            abort(501);
        }

        try{
            Log::info(new \Exception('download', 200), $request,
                [
                    'action' => 'download',
                    'resource' => $this->resource,
                ]
            );

            $this->beforeDownload($request, $path);

            return response()->download(storage_path(base64_decode($path)));
        }
        catch (\Exception $e){
            Log::info(new \Exception('download', 200), $request,
                [
                    'action' => 'download',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Called by child class, execute operation before downloaded
     * @param Request $request
     * @param null $path
     */
    protected function beforeDownload(Request $request, $path = NULL)
    {

    }
}