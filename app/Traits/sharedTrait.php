<?php

namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
Trait  sharedTrait

{
    function save(Request $request  ,$destination , $filename , $path )
    {
      if($request->hasfile($filename ))
      {

          if (File::exists($destination ))
          {
              File::delete($destination);
          }
          $file = $request->file($filename);
          $extention = $file->getClientOriginalExtension();
          $fileName = time().'.'.$extention;
          $file->move('uploads/'.$path.'/', $fileName);

     return $fileName;
      }
    }

  public function slug($string, $separator = '-') {
    if (is_null($string)) {
        return "";
    }

    $string = trim($string);

    $string = mb_strtolower($string, "UTF-8");;

    $string = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

    $string = preg_replace("/[\s-]+/", " ", $string);

    $string = preg_replace("/[\s_]/", $separator, $string);

    return $string;
}









}

?>
