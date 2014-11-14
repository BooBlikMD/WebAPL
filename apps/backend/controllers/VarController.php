<?php

/**
 * 
 * CMS WebAPL 1.0. Platform is a free open source software for creating an managing
 * their full with CMS integrated CMS system
 * 
 * Copyright (C) 2014 Enterprise Business Solutions SRL
 * 
 * This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 * You can read the copy of GNU General Public License in english here 
 * 
 * For more details about CMS WebAPL 1.0 please contact Enterprise Business
 * Solutions SRL, Republic of Moldova, MD 2001, Ion Inculet 33 Street or send an
 * email to office@ebs.md 
 * 
 */
class VarController extends BaseController {

    function __construct() {
        parent::__construct();

        $this->beforeFilter(function() {
            if (!Auth::check()) {
                return Redirect::to('auth');
            }
        });
    }

    protected $layout = 'layout.main';

    public function getIndex($var_key = '') {
        User::onlyHas('var-edit');

        $this->data['var'] = VarModel::prepareQuery()->where('key', $var_key)->first();
        if ($this->data['var'] || $var_key === '') {
            $this->data['var_key'] = $var_key;
            $this->data['var_list'] = VarModel::withParent($var_key);

            if ($this->data['var']) {
                $this->data['var_parents'] = VarModel::getParents($this->data['var']->parent_key);
            } else {
                $this->data['var_parents'] = [];
            }

            $this->layout->content = View::make('sections.var.list', $this->data);
        } else {
            throw new Exception("Var not found '{$var_key}'");
        }
    }

    public function postSearch() {
        User::onlyHas('var-edit');

        $query = Input::get('varname');

        $list = VarLangModel::where(VarLangModel::getField('value'), 'like', "%{$query}%")->get();

        $count = count($list);

        if ($count == 0) {
            return \Illuminate\Support\Facades\Redirect::back()->with('searchfail', 1);
        } elseif ($count == 1) {
            $item = $list[0];
            return \Illuminate\Support\Facades\Redirect::to('var/index/' . $item->var_key);
        } else {
            return \Illuminate\Support\Facades\Redirect::back()->with('searchfail', 0)->with('searchresult', $list->toArray());
        }
    }

    public function postCreate() {
        User::onlyHas('var-create');

        $parent_key = Input::get('parent_key');
        $var_langs = Input::get('text');
        $key = VarModel::uniqKey(Input::get('key'), Input::get('text.' . (\WebAPL\Language::getId())));

        $var = new VarModel;
        $var->key = $key;
        $var->parent_key = $parent_key;
        $var->save();

        foreach ($var_langs as $lang_id => $value) {
            $var_lang = new VarLangModel;
            $var_lang->var_key = $key;
            $var_lang->lang_id = $lang_id;
            $var_lang->value = $value;
            $var_lang->save();
        }

        Log::info("Created new var '{$key}'");

        return Redirect::back();
    }

    public function postEdit() {
        User::onlyHas('var-edit');

        $id = Input::get('id');
        $value = Input::get('value');

        $vlang = VarLangModel::find($id);
        $vlang->value = $value;
        $vlang->save();

        return [];
    }

    public function getExport() {


        //$exist = explode(',', '1,2,300,400,3,4,301,401,5,6,302,7,8,303,9,10,304,12,13,305,15,16,306,18,19,307,407,21,22,308,408,24,25,309,409,30,31,311,411,33,34,312,412,36,37,313,413,39,40,314,414,42,43,315,415,45,46,316,416,48,49,317,417,51,52,318,418,54,55,319,419,57,58,320,60,61,321,63,64,322,422,66,67,323,72,73,324,75,76,325,78,79,326,81,82,327,427,84,85,328,87,88,329,90,91,330,93,94,331,96,97,332,99,100,333,102,103,334,434,105,106,335,108,109,336,436,111,112,337,114,115,338,438,117,118,339,120,121,340,440,123,124,341,126,127,342,129,130,343,132,133,344,135,136,345,138,139,346,141,142,347,144,145,348,147,148,349,150,151,350,153,154,351,451,156,157,352,159,160,353,162,163,354,165,166,355,168,169,356,171,172,357,174,175,358,177,178,359,180,181,360,183,184,361,186,187,362,189,190,363,192,193,364,195,196,365,198,199,366,201,202,367,204,205,368,207,208,369,210,211,370,213,214,371,216,217,372,219,220,373,222,223,374,225,226,375,228,229,376,231,232,377,234,235,378,237,238,379,240,241,380,243,244,381,246,247,382,249,250,383,252,253,384,255,256,385,258,259,386,261,262,387,264,265,388,267,268,389,270,271,390,273,274,391,276,277,392,279,280,393,282,283,394,285,286,395,288,289,396,291,292,397,294,295,398,297,298,399,500,501,502,504,505,506,508,509,510,512,513,514,516,517,518,520,521,522,524,525,526,528,529,530,532,533,534,536,537,538,540,541,542,544,545,546,548,549,550,552,553,554,556,557,558,560,561,562,564,565,566,568,569,570,572,573,574,576,577,578,580,581,582,584,585,586,588,589,590,592,593,594,596,597,598,600,601,602,604,605,606,608,609,610,612,613,614,616,617,618,620,621,622,623,624,625,626,628,629,630,632,633,634,636,637,638,640,641,642,644,645,646,648,649,650,652,653,654,656,657,658,660,661,662,664,665,666,668,669,670,672,673,674,676,677,678,680,681,682,684,685,686,688,689,690,692,693,694,696,697,698,700,701,702,704,705,706,708,709,710,712,713,714,716,717,718,720,721,722,724,725,726,728,729,730,732,733,734,736,737,738,740,741,742,744,745,746,748,749,750,752,753,754,756,757,758,760,761,762,764,765,766,768,769,770,772,773,774,776,777,778,780,781,782,784,785,786,788,789,790,792,793,794,796,797,798,800,801,802,804,805,806,808,809,810,812,813,814,816,817,818,820,821,822,824,825,826,828,829,830,832,833,834,836,837,838,840,841,842,844,845,846,848,849,850,852,853,854,856,857,858,860,861,862,864,865,866,868,869,870,872,873,874,876,877,878,880,881,882,883,884,885,886,887,888,889,890,892,893,894,895,896,897,898,900,901,902,904,905,906,908,909,910,912,913,914,916,917,918,920,921,922,924,925,926,928,929,930,932,933,934,936,937,938,940,941,942,944,945,946,948,949,950,952,953,954,956,957,958,960,961,962,964,965,966,968,969,970,972,973,974,976,977,978,980,981,982,984,985,986,988,989,990,992,993,994,996,997,998,1000,1001,1002,1004,1005,1006,1008,1009,1010,1012,1013,1014,1016,1017,1018,1020,1021,1022,1024,1025,1026,1028,1029,1030,1032,1033,1034,1036,1037,1038,1040,1041,1042,1044,1045,1046,1048,1049,1050,1052,1053,1054,1056,1057,1058,1060,1061,1062,1064,1065,1066,1068,1069,1070,1072,1073,1074,1076,1077,1078,1080,1081,1082,1084,1085,1086,1088,1089,1090,1092,1093,1094,1096,1097,1098,1100,1101,1102,1104,1105,1106,1108,1109,1110,1112,1113,1114,1116,1117,1118,1120,1121,1122,1124,1125,1126,1128,1129,1130,1131,1132,1133,1134,1136,1137,1138,1140,1141,1142,1144,1145,1146,1148,1149,1150,1152,1153,1154,1156,1157,1158,1160,1161,1162,1164,1165,1166,1168,1169,1170,1172,1173,1174,1176,1177,1178,1180,1181,1182,1184,1185,1186,1188,1189,1190,1192,1193,1194,1195,1196,1197,1198,1200,1201,1202,1204,1205,1206,1208,1209,1210,1212,1213,1214,1216,1217,1218,1220,1221,1222,1224,1225,1226,1228,1229,1230,1236,1237,1238,1240,1241,1242,1244,1245,1246,1248,1249,1250,1252,1253,1254,1256,1257,1258,1260,1261,1262,1264,1265,1266,1268,1269,1270,1272,1273,1274,1276,1277,1278,1280,1281,1282,1284,1285,1286,1288,1289,1290,1292,1293,1294,1296,1297,1298,1300,1301,1302,1304,1305,1306,1308,1309,1310,1312,1313,1314,1316,1317,1318,1320,1321,1322,1324,1325,1326,1328,1329,1330,1332,1333,1334,1336,1337,1338,1339,1340,1341,1342,1344,1345,1346,1348,1349,1350,1352,1353,1354,1356,1357,1358,1360,1361,1362,1364,1365,1366,1368,1369,1370,1371,1372,1373,1374,1376,1377,1378,1380,1381,1382,1384,1385,1386,1388,1389,1390,1392,1393,1394,1396,1397,1398,1400,1401,1402,1404,1405,1406,1408,1409,1410,1412,1413,1414,1416,1417,1418,1420,1421,1422,1424,1425,1426,1428,1429,1430,1432,1433,1434,1436,1437,1438,1440,1441,1442,1444,1445,1446,1448,1449,1450,1452,1453,1454,1456,1457,1458,1460,1461,1462,1464,1465,1466,1468,1469,1470,1472,1473,1474,1476,1477,1478,1480,1481,1482,1484,1485,1486,1488,1489,1490,1492,1493,1494,1496,1497,1498,1500,1501,1502,1504,1505,1506,1508,1509,1510,1512,1513,1514,1516,1517,1518,1520,1521,1522,1524,1525,1526,1528,1529,1530,1532,1533,1534,1536,1537,1538,1540,1541,1542,1544,1545,1546,1548,1549,1550,1552,1553,1554,1556,1557,1558,1560,1561,1562,1564,1565,1566,1568,1569,1570,1572,1573,1574,1576,1577,1578,1580,1581,1582,1584,1585,1586,1588,1589,1590,1592,1593,1594,1596,1597,1598,1600,1601,1602,1604,1605,1606,1608,1609,1610,1612,1613,1614,1616,1617,1618,1620,1621,1622,1624,1625,1626,1628,1629,1630,1632,1633,1634,1636,1637,1638,1640,1641,1642,1644,1645,1646,1648,1649,1650,1652,1653,1654,1656,1657,1658,1660,1661,1662,1664,1665,1666,1668,1669,1670,1672,1673,1674,1676,1677,1678,1680,1681,1682,1684,1685,1686,1688,1689,1690,1692,1693,1694,1696,1697,1698,1700,1701,1702,1704,1705,1706,1708,1709,1710,1712,1713,1714,1716,1717,1718,1720,1721,1722,1724,1725,1726,1728,1729,1730,1732,1733,1734,1736,1737,1738,1740,1741,1742,1744,1745,1746,1748,1749,1750,1752,1753,1754,1756,1757,1758,1760,1761,1762,1764,1765,1766,1768,1769,1770,1772,1773,1774,1776,1777,1778,1780,1781,1782,1784,1785,1786,1788,1789,1790,1792,1793,1794,1796,1797,1798,1800,1801,1802,1804,1805,1806,1808,1809,1810,1812,1813,1814,1816,1817,1818,1820,1821,1822,1824,1825,1826,1828,1829,1830,1832,1833,1834,1836,1837,1838,1840,1841,1842,1844,1845,1846,1848,1849,1850,1852,1853,1854,1856,1857,1858,1860,1861,1862');
        //var_dump(($exist));
        
        $buffer = "";

        $vars = VarModel::all();

        $num = 0;

        $vlang_ids = array();

        foreach ($vars as $var) {
            $langs = VarLangModel::where('var_key', $var->key)->get();
            foreach ($langs as $vlang) {
//                if (in_array($vlang->id, $exist)) {
//                    continue;
//                }
                $langname = WebAPL\Language::getItem($vlang->lang_id)->name;
                $buffer .= "{$vlang->id},{$langname},\"{$vlang->value}\"\n";

                $vlang_ids[] = $vlang->id;

                $num++;
            }
            $buffer .= "\n";
        }

        $buffer .= "{$num}\n";

        //var_dump(count($vlang_ids), VarLangModel::whereNotIn('id', $vlang_ids)->get());
        //return [];

        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename=Customers_Export.csv');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        return $buffer;
    }

    public function getImport() {
        return [];
        
        $xsdstring = $_SERVER['DOCUMENT_ROOT'] . "/vars-list.xml";

        $excel = new XML2003Parser($xsdstring);

        $table = $excel->getTableData();
        $ids = [];
        foreach ($table["table_contents"] as $row) {
            if (isset($row["row_contents"][2]) && isset($row["row_contents"][0])) {
                $id = $row["row_contents"][0]['value'];
                if ($id) {
                    $value = $row["row_contents"][2]['value'];
                    $varlang = VarLangModel::find($id);
                    if ($varlang) {
                        if ($varlang->value !== $value && strlen(trim($varlang->value)) > 0) {
                            echo "DIFF [{$varlang->lang_id}] [{$varlang->id}] [[{$varlang->value}]] [[{$value}]]<br>\n";
                        }

                        $varlang->value = $value;

//                        //$varlang->value = $value;
                        $varlang->save();
//                        $ids[] = $id;
                    } else {
                        echo "interzis [{$varlang->lang_id}] {$id} {$varlang} <br>\n";
                    }
                } else {
                    echo "clear<br>\n";
                }
            }
        }

        //echo implode(',', $ids);

        return [];
    }

}
