<?php
namespace App\Http\Traits;

trait FileUpload {
    public function upload($request,$directory = null){
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('/uploads/'.$directory), $imageName);

        return isset($directory) ?
            '/uploads/'.$directory.'/'.$imageName :
            '/uploads/'.$imageName;
    }
}

?>
