<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*-----------------------------*/

require 'src/Climatempo.php';
require 'src/Wrapper.php';
require 'src/Forecast.php';
require 'src/FifteenDays.php';

use MeTempo\Climatempo\Climatempo;

/*-----------------------------*/
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$token      = 'b254f313c5c11bd4fba98d8b73017a14';
$climatempo = new Climatempo($token);

?>
<!DOCTYPE html>
<html lang="en">
<!-- start: META -->

<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="" name="author" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!---- end: Meta --->
<?php
require 'resources/header.html';
?>
    <body>
        <div class="container" >
            <div class="row" id="busca">
                <div class="row title" align="center">
                    <h3>Pesquisar Tempo</h3>
                </div>
                <form method="post">

                    <div class="col-md-3 col-md-offset-3">
                        <select class="form-control" name="uf" id="uf">
                            <option value="">Selecionar UF</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM</option>
                            <option value="AP">AP</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MG">MG</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="PR">PR</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SE">SE</option>
                            <option value="SP">SP</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="municipio" id="municipio">
                            <option value="">Selecionar Município</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-md-offset-3" id="button">
                        <button class="btn btn-primary btn-block">Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
        <hr/>
        <?php

        if($_POST):
            $id         = $_POST['municipio']; // São Paulo - SP

            try {
                $forecast = $climatempo->fifteenDays($id);
            } catch (Exception $e) {
                echo '<b>Error: </b>'.$e->getMessage();
                die();
            }

        ?>

        <div class="container-fluid" id="resultado">
            <h2><?= $forecast->name .'/'. $forecast->state .'-'. $forecast->country ?></h2>
            <div class="row cbm_wrap ">
                <?php for ($i=0; $i<=6; $i++) :
                    $dia_semana =  utf8_encode(strftime('%A', strtotime($forecast->data[$i]->date)));
                    ;?>
                    <?php if($i == 0 ):?>

                        <div class="col-md-5 col-sm-12 box-hoje" align="center">
                            <h2>Hoje</h2>
                            <b class="hidden" id="dia_<?=$i?>"><?=date('d M, Y', strtotime($forecast->data[$i]->date))?></b>
                            <img src="resources/images/<?=$forecast->data[$i]->dayIcon?>.png" /> <br>
                            <b class="temp-min temp_font"><span id="min_dia_<?=$i?>"><?=$forecast->data[$i]->minTemperature?></span>°C</b> -
                            <b class="temp-max temp_font"><span id="max_dia_<?=$i?>"><?=$forecast->data[$i]->maxTemperature?></span>°C</b> <br>
                            <hr>
                            <div class="row periodo">
                            <div class="col-md-4 co-sm-4 col-xs-4">Manhã:<br/>
                                <b class="temp-max"><?=$forecast->data[$i]->minMorningTemp?>°C</b> -
                                <b class="temp-max"><?=$forecast->data[$i]->maxMorningTemp?>°C</b> <br>
                            </div>
                            <div class="col-md-4 co-sm-4 col-xs-4">Tarde:<br/>
                                <b class="temp-max"><?=$forecast->data[$i]->minAfternoonTemp?>°C</b> -
                                <b class="temp-max"><?=$forecast->data[$i]->maxAfternoonTemp?>°C</b> <br>
                            </div>
                            <div class="col-md-4 co-sm-4 col-xs-4">Noite:<br/>
                                <b class="temp-max"><?=$forecast->data[$i]->minNightTemp?>°C</b> -
                                <b class="temp-max"><?=$forecast->data[$i]->maxNightTemp?>°C</b> <br>
                            </div>
                            </div>
                        </div>
                    <?php else:?>
                        <div class="col-md-2 border-box" align="center">
                            <div class="row ">
                                <h4><?=$dia_semana?></h4>
                                <span><?=date('d/m', strtotime($forecast->data[$i]->date))?></span>
                                <b class="hidden" id="dia_<?=$i?>"><?=date('d M, Y', strtotime($forecast->data[$i]->date))?></b>
                            </div>
                            <img src="resources/images/<?=$forecast->data[$i]->dayIcon?>.png" /> <br>

                            <b class="temp-min"><span id="min_dia_<?=$i?>"><?=$forecast->data[$i]->minTemperature?></span>°C</b> -
                            <b class="temp-max"><span id="max_dia_<?=$i?>"><?=$forecast->data[$i]->maxTemperature?></span>°C</b> <br>

                            <?php
                            if($dia_semana == 'domingo'):
                                echo $dia_semana;?>

                                <div class="col-md-12 balao">
                                    <b><?=$forecast->data[$i]->resume?></b>
                                </div>
                            <?php elseif($dia_semana == 'sábado'): ?>
                                <div class="col-md-12 balao">
                                    <b><?=$forecast->data[$i]->resume?></b>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif;?>
                 <?php endfor; ?>
            </div>
        </div>
        <div id="chartContainer"></div>
<?php
endif;
?>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="resources/canvasjs.min.js"></script>
<script>
    window.onload = function () {
       max_dia_0 = parseInt($('#max_dia_0').text());
       max_dia_1 = parseInt($('#max_dia_1').text());
       max_dia_2 = parseInt($('#max_dia_2').text());
       max_dia_3 = parseInt($('#max_dia_3').text());
       max_dia_4 = parseInt($('#max_dia_4').text());
       max_dia_5 = parseInt($('#max_dia_5').text());
       max_dia_6 = parseInt($('#max_dia_6').text());


        min_dia_0 = parseInt($('#min_dia_0').text());
        min_dia_1 = parseInt($('#min_dia_1').text());
        min_dia_2 = parseInt($('#min_dia_2').text());
        min_dia_3 = parseInt($('#min_dia_3').text());
        min_dia_4 = parseInt($('#min_dia_4').text());
        min_dia_5 = parseInt($('#min_dia_5').text());
        min_dia_6 = parseInt($('#min_dia_6').text());

        dia_0 = $('#dia_0').text();
        dia_1 = $('#dia_1').text();
        dia_2 = $('#dia_2').text();
        dia_3 = $('#dia_3').text();
        dia_4 = $('#dia_4').text();
        dia_5 = $('#dia_5').text();
        dia_6 = $('#dia_6').text();

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Gráfico"
            },
            axisX: {
                valueFormatString: "DD MMM,YY"
            },
            axisY: {
                title: "Temperatura (°C)",
                includeZero: false,
                suffix: " °C"
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip:{
                shared: true
            },

            data: [{
                name: "Temperatura Máxima",
                type: "spline",
                yValueFormatString: "#0.## °C",
                showInLegend: true,
                dataPoints: [
                    { x: new Date(dia_0), y: max_dia_0 },
                    { x: new Date(dia_1), y: max_dia_1 },
                    { x: new Date(dia_2), y: max_dia_2 },
                    { x: new Date(dia_3), y: max_dia_3 },
                    { x: new Date(dia_4), y: max_dia_4 },
                    { x: new Date(dia_5), y: max_dia_5 },
                    { x: new Date(dia_6), y: max_dia_6 }
                ]
            },
                {
                    name: "Temperatura Mínima",
                    type: "spline",
                    yValueFormatString: "#0.## °C",
                    showInLegend: true,
                    dataPoints: [
                        { x: new Date(dia_0), y: min_dia_0 },
                        { x: new Date(dia_1), y: min_dia_1 },
                        { x: new Date(dia_2), y: min_dia_2 },
                        { x: new Date(dia_3), y: min_dia_3 },
                        { x: new Date(dia_4), y: min_dia_4 },
                        { x: new Date(dia_5), y: min_dia_5 },
                        { x: new Date(dia_6), y: min_dia_6 }
                    ]
                }]
        });
        chart.render();

        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
    $('#uf').change(function(){
        $('#municipio').empty()
        $('#municipio').append("<option value=''>Selecionar Município</option> ");
        uf = $(this).val()
        var url = "src/cities.json";
        $.getJSON(url, function (data) {
            count = data[uf].length
            for(i = 0; i <= count; i++){
                id = data[uf][i]['id'];
                mun = data[uf][i]['name'];
                $('#municipio').append("<option value='"+id+"'>"+mun+"</option>")
            }


        });
    });
</script>
