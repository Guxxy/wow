<?php
include("connect.php");

$region ="eu";
$realm = "Mazrigos";
$guild = "Outcast";


$ranks = array(
'Officer', // 0
'Officer', // 1
'Officer Alt', // 2
'Raider', // 3
'Alt', // 4
'Trial', // 5
'Social' // 6
);


$json = file_get_contents("https://eu.api.battle.net/wow/guild/$realm/$guild?fields=members&locale=en_GB&apikey=3sj8kdrjpndhgud9hkd3k7q5gggck7fx");


//if($json == false) {
//throw new Exception("Failed To load infomation.");
//}


$decode = json_decode($json, true);
$rows = $decode['members'];
$rows=array();
foreach ($decode['members'] as $i => $e) {
$rows[$i]['rank'] = $e['rank'];
$rows[$i]['name'] = $e['character']['name'];
$rows[$i]['class'] = $e['character']['class'];
$rows[$i]['race'] = $e['character']['race'];
$rows[$i]['level'] = $e['character']['level'];
$rows[$i]['gender'] = $e['character']['gender'];
}


$s = (isset($_GET['s']) ? $_GET['s'] : '');
$u = (isset($_GET['u']) ? $_GET['u'] : '0');


if ($s != '') {
sksort($rows,$s,$u);
}
else {
sksort($rows,'rank',true);
}


//Guild Roster Table Headers
echo " <div width='600px' align#'center'>";
echo '
<div align="center" id="roster" class="roster" style="float: none;">
<table class="warcraft sortable" border="3" cellspacing="0" cellpadding="0" align="center">
<tr>
<th width="80px" align="center" valign="top" ><strong>Race</strong></a></th>
<th width="140px" align="center" valign="top" ><strong>Name</strong></a></th>
<th width="80px" align="center" valign="top" ><strong>Level</strong></a></th>
<th width="140px" align="center" valign="top" ><strong>Rank</strong></a></th>
</tr>';


//Character Arrays
foreach($rows as $p) {
$mrank = $p['rank'];
$mname = $p['name'];
$mclass = $p['class'];
$mrace = $p['race'];
$mlevel = $p['level'];
$mgender = $p['gender'];


if ($mrank == 2 || $mrank == 4 || $mrank == 6 || $mrank == 8) {
continue;
}
//Table of Guild Members
echo "
<tr>
<td align='center'><strong><img style=\"padding-left: 5px;\" src=\"race/$mrace-$mgender.gif\"></img><img style=\"padding-left: 5px;\" src=\"class/$mclass.gif\"></img></strong></td>
<td class='class_$mclass' width=\"140px\" align=\"center\" valign=\"top\" ><a href=\"http://$region.battle.net/wow/en/character/$realm/$mname/simple\"><strong>$mname</strong></a></td>
<td width=\"80px\" align=\"center\" valign=\"top\" ><strong>$mlevel</strong></td>
<td sorttable_customkey='$mrank' width=\"140px\" align=\"center\" valign=\"top\" ><strong>$ranks[$mrank]</strong></td>
</tr>
";
}


echo " </table></div>";


function sksort(&$array, $subkey="id", $sort_ascending=false) 
{
if (count($array))
$temp_array[key($array)] = array_shift($array);


foreach($array as $key => $val) {
$offset = 0;
$found = false;
foreach($temp_array as $tmp_key => $tmp_val) {
if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
$temp_array = array_merge(
(array)array_slice($temp_array,0,$offset),
array($key => $val),
array_slice($temp_array,$offset)
);
$found = true;
}
$offset++;
}
if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
}


if ($sort_ascending)
$array = array_reverse($temp_array);
else 
$array = $temp_array;
}


echo " </table></div>";


?>
