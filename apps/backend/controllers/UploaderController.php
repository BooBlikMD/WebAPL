<?php

/**
 * 
 *
 * @author     Godina Nicolae <ngodina@ebs.md>
 * @copyright  2014 Enterprise Business Solutions SRL
 * @link       http://ebs.md/
 */


class UploaderController extends BaseController {

    function __construct() {
        parent::__construct();

        $this->beforeFilter(function() {
            if (!Auth::check()) {
                return Redirect::to('auth');
            }
        });
    }

    /**
     * Get filelist
     */
    public function filelist() {
        $files = Files::file_list(Input::get('module_name'), Input::get('module_id'));

        $data = array();
        $data['html'] = View::make('sections.file.file_list')->with(array(
                    'module_name' => Input::get('module_name'),
                    'module_id' => Input::get('module_id'),
                    'num' => Input::get('num'),
                    'files' => $files
                ))->render();

        return $data;
    }

    /**
     * Upload file
     * @return array
     */
    public function start() {
        $data = array(
            'module_id' => Input::get('module_id'),
            'module_name' => Input::get('module_name'),
            'num' => Input::get('num')
        );
        if (Input::hasFile('upload_file')) {
            $file = Input::file('upload_file');

            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();

            $filename = uniqid() . '_' . md5($name) . "." . $extension;

            $uploadSuccess = $file->move(Files::fullDir(), $filename);

            if ($uploadSuccess) {
                $fid = Files::register($name, $filename, $extension, $data['module_name'], $data['module_id']);

                $data['error'] = '0';
                $data['succes'] = 'Uploaded!';
                
                Log::info("File uploaded #{$fid} - '{$filename}'");
            } else {
                $data['error'] = '1';
                $data['succes'] = 'Error!';
                
                Log::warning("Error upload file {$name}");
            }
        } else {
            $data['error'] = '1';
            $data['succes'] = 'Upload file not found';
        }
        return $data;
    }

    /**
     * Ajax delete file
     * @return array
     */
    public function delete() {
        $id = Input::get('id');
        $data = array(
            'deleted' => 0
        );
        if ($id) {
            $data['deleted'] = Files::drop($id);
        }
        return $data;
    }
    
    
    public function editname() {
        $id = Input::get('id');
        $name = Input::get('name');
        
        $file = Files::find($id);
        
        if ($file) {
            $file->name = $name;
            $file->save();
        } else {
            throw new Exception("File not found #{$id}");
        }
    }

}
