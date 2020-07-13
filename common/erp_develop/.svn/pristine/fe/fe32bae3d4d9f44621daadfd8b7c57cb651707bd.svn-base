<?php 
// check we have data
if(!isset($_GET["field_name"])) die("error with data");
if(!isset($_GET["type"]))        die("Error with data");
 
// define vars (should do more checking here)
$field_name    =    $_GET["field_name"];
$form_type    =    $_GET["type"];
$num        =    $_GET["num"];
 
switch($form_type){
 case "item":
 echo '
 <table id="table_'.$num.'">
 <tr>
 <td rowspan="4">Table '.$num.'</td>
 <td>Image</td>
 <td><input type="file" name="file_2_upload[]" style="width:99%;"></td>
 </tr>
 <tr>
 <td>Title</td>
 <td><input type="text" name="'.$field_name.'[title][]" value="" style="width:99%;"></td>
 </tr>
 <tr>
 <td>Color</td>
 <td>
 <select name="'.$field_name.'[options]">
 <option value="1">White</option>
 <option value="2">Red</option>
 <option value="3">Blue</option>
 <option value="4">Yellow</option>
 </select>
 </td>
 </tr>
 <tr>
 <td>Description</td>
 <td><textarea name="'.$field_name.'[summary][]" style="width:99%; height:100px;"></textarea></td>
 </tr>
 </table>
 ';
 break;
 default:
 echo "nothing doing, sorry";
 break;
}

?>