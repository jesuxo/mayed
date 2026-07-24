<?php
$linkfacturacion = $_SESSION['company_row']['linkfacturacion'];
if(!isset($empresa)){
        session_start();
        include('conex.php');
        include('conexms.php');
        include('funcionesphp.php');
        $weburl          = $_SESSION['company_row']['web_url'];
        $websucursal     = $_SESSION['company_row']['web_sucursal'];
        $sistemaoptico   = (isset($_SESSION['company_row']['sistemaoptico']))? $_SESSION['company_row']['sistemaoptico'] : 0;

        $companyid       = $_SESSION['company_row']['id'];
        $companydb       = $_SESSION['company_row']['db'];
        $porcPrecios     = $_SESSION['company_row']['porcPrecios'];
        $empresa         = $_SESSION['company_row']['empresa'];
        $porcIncreClie   = $_SESSION['company_row']['porcIncreClie'];
        $codCompleto     = $_SESSION['company_row']['codCompleto'];
        $anticipoProv    = $_SESSION['company_row']['anticipoProv'];
        $anticipoClie    = $_SESSION['company_row']['anticipoClie'];
        $colorlinknota   = $_SESSION['company_row']['colorlinknota'];
        $idsession       = $_SESSION["id"];
        $factorcambio1   = $_SESSION['company_row']['factorcambio1'];
        $precioenpesos   = $_SESSION['company_row']['precioenpesos'];
        $repmovunidades  = $_SESSION['company_row']['repmovunidades'];
        $newprodInstGral = $_SESSION['company_row']['newprodInstGral'];
        $prodcolor       = $_SESSION['company_row']['prodcolor'];
        $decimalesPrecio = $_SESSION['company_row']['decimalesPrecio'];
        $linkfacturacion = $_SESSION['company_row']['linkfacturacion'];
        if(!$decimalesPrecio) $decimalesPrecio = 2;


        $precio1bsx         = $_SESSION['company_row']['precio1bsx'];
        $precio2bsx         = $_SESSION['company_row']['precio2bsx'];
        $precio3bsx         = $_SESSION['company_row']['precio3bsx'];



        $precionoeditable = 0;

        if(($precio1bsx ==0 or $precio2bsx ==0 or $precio3bsx ==0) and ($precio1bsx ==1 or $precio2bsx ==1 or $precio3bsx ==1) ) {
            $precionoeditable = 1;
        }


    $porcIncrementa = 0;

    if($porcIncreClie > 0 and isset($_SESSION['codclie']) and $_SESSION['codclie'] != ''){
        $pasaincre = 0;
        $sqlimcrementa  = "SELECT porcIncrementa
                       FROM $companydb.dbo.SACLIE 
                       WHERE codclie = '".$_SESSION['codclie']."'";

        $resincreme     = mssql_query($sqlimcrementa,$linkms) or die(mssql_get_last_message());
        $listaincre     = mssql_fetch_array($resincreme);
        $porcIncrementa = (isset($listaincre['porcIncrementa']) and $listaincre['porcIncrementa'] >0)? $listaincre['porcIncrementa'] : 0;

        if($porcIncrementa){
            $pasaincre = 1;
        }
    }


        $productosbs         = $_SESSION['company_row']['productosbs'];
        $appurl              = $_SESSION['company_row']['app_url'];
        $tasasorder          = $_SESSION['company_row']['tasasorder'];
        $ocultarCOP          = $_SESSION['company_row']['ocultarCOP'];
        $newAnticipoProv     = $_SESSION['company_row']['newAnticipoProv'];
        $newAnticipoClie     = $_SESSION['company_row']['newAnticipoClie'];
        $facturacionPedidos  = $_SESSION['company_row']['facturacionPedidos'];
        $soloinvnota         = $_SESSION['company_row']['soloinvnota'];
        $paneldeprocesos     = $_SESSION['company_row']['paneldeprocesos'];
        $pedidosdesactivados = $_SESSION['company_row']['pedidosdesactivados'];
        $nadaNota            = $_SESSION['company_row']['nadaNota'];
        $aprobarTraslados    = $_SESSION['company_row']['aprobarTraslados'];
        $prodcontrola        = $_SESSION['company_row']['prodcontrolados'];
        $sesionfacturatxt    = $_SESSION['company_row']['sesionfacturatxt'];
        $sesionfacturahoja   = $_SESSION['company_row']['sesionfacturahoja'];
        $repVentaEstaciones  = $_SESSION['company_row']['repVentaEstaciones'];if(!$repVentaEstaciones) $repVentaEstaciones =0;
        $transferenciaURL    = $_SESSION['company_row']['transferenciaURL'];
        $productMaxLength    = $_SESSION['company_row']['productMaxLength'];
        $manejoCajasPzas     = $_SESSION['company_row']['manejoCajasPzas'];
        $reporteBonificado   = $_SESSION['company_row']['reporteBonificado'];
        $editableDescripInic = $_SESSION['company_row']['editableDescripInicio'];

        if(!isset($buscarproducto))         $buscarproducto = '';
        if(!isset($codservdmodificar))      $codservdmodificar = '';
        if(!isset($tab))                    $tab = '';
        if(!isset($operacioncargodescargo)) $operacioncargodescargo = '';



    $sqlcurso   = "select valporc, pesoxdolar, eurodolar, mescursow , tasabs, tasadolar, tasaeuro, tasapeso 
   				   from $companydb.dbo.saconf
                   where valporc>0 or pesoxdolar >0";
    $resporc    = mssql_query($sqlcurso, $linkms) or die(mssql_get_last_message());
    $listms     = mssql_fetch_array($resporc);
    $valporc    = $listms['valporc'];
    $mescurso   = $listms['mescursow'];
    $pesoxdolar = $listms['pesoxdolar'];
    $eurodolar  = $listms['eurodolar'];
    $tasabs     = $listms['tasabs'];
    $tasadolar  = $listms['tasadolar'];
    $tasaeuro   = $listms['tasaeuro'];
    $tasapeso   = $listms['tasapeso'];

}

if(!isset($_SESSION['precio123']))
    $_SESSION['precio123'] = 3;

if(    ($precio1bsx == 1 and $_SESSION['precio123'] == 1)
    or ($precio2bsx == 1 and $_SESSION['precio123'] == 2)
    or ($precio3bsx == 1 and $_SESSION['precio123'] == 3) )
    $ocultarbstotal = 1;
?>

<div style="   display: flex;flex-direction: column; ">
    <div id="verproducto" style="margin-bottom:20px">

        <?
        $datacont = "";
        if(isset($codprodmodificar) and $codprodmodificar!=''   ){

            if($precioenpesos)
                $fieldfijo = " a.preciodolarfijo,";

            if($prodcontrola)
                $datacont = " controlado ,";

            if($manejoCajasPzas == 1)
                $datacont .= "  mtscaja  ,  mtspieza,";


            $sql="SELECT   b.desseri,  a.DEsComp, a.marca, a.descrip2 as producto2,  a.descrip3 as producto3, a.unidad, a.ExDecimal, a.consignacion, a.prodmayor,  $datacont
                           a.descrip4 as producto4,  RIGHT(CONVERT(VARCHAR(10), a.fechauv, 103), 10) AS fechauv,  $fieldfijo  
                           a.descrip as producto, a.marca, a.refere, a.codprod, b.descrip as instancia, a.existen, a.esbarato, 
                           a.esexento,   a.peso, a.costact as costoprod, a.codinst, a.precio1,a.precio2,a.precio3, a.publicado,
                           a.peso, a.volumen, a.cantxempaq, a.activo
                  FROM $companydb.dbo.saprod as a, $companydb.dbo.sainsta as b
                  WHERE a.codprod='$codprodmodificar'
                  and a.codinst=b.codinst
		  ";

            $res            = mssql_query($sql,$linkms);
            $listproducto   = mssql_fetch_array($res);

            $DEsComp        = $listproducto['DEsComp'];
            $desseri        = $listproducto['desseri'];
            $costoprod		= $listproducto['costoprod'];
            $fechauv        = $listproducto['fechauv'];
            $ExDecimal      = $listproducto['ExDecimal'];
            $producto2		= $listproducto['producto2'];if($producto2==' ')$producto2='';
            $producto4		= $listproducto['producto4'];if($producto4==' ')$producto4='';
            $producto3		= $listproducto['producto3'];if($producto3==' ')$producto3='';
            $pesoprod		= $listproducto['peso'];
            $unidadprod		= $listproducto['unidad'];
            $volumenprod	= $listproducto['volumen'];
            $consignacion   = $listproducto['consignacion'];
            $prodmayor      = $listproducto['prodmayor'];
            $controlado     = $listproducto['controlado'];
            $cantxempaq     = $listproducto['cantxempaq'];
            $instancia		= $listproducto['instancia'];
            $preciodolarfijo = $listproducto['preciodolarfijo'];
            $codinstprd		= $listproducto['codinst'];
            $mtscaja		= $listproducto['mtscaja'];
            $mtspieza		= $listproducto['mtspieza'];
            $esexento		= $listproducto['esexento'];
            $prodexento     = $esexento;
            $prodpublicado  = $listproducto['publicado'];
            $prodactivo     = $listproducto['activo'];
            $producto		= $listproducto['producto'];
            $esbarato		= $listproducto['esbarato'];
            $valorpeso		= $listproducto['valor'];
            $codprod		= $listproducto['codprod'];
            $refere			= $listproducto['refere'];if($refere==' ')$refere='';
            $fventa			= $listproducto['fventa'];
            $venta			= $listproducto['uventa'];
            $marca			= $listproducto['marca'];if($marca==' ')$marca='';
            $peso			= $listproducto['peso'];
            $precio1		= $listproducto['precio1'];
            $precio2		= $listproducto['precio2'];
            $precio3		= $listproducto['precio3'];
            $precio1aux		= $listproducto['precio1'];
            $precio2aux		= $listproducto['precio2'];
            $precio3aux		= $listproducto['precio3'];

            if($esexento==1){}else{
                $precio1 = $listproducto['precio1'] * (1+($_SESSION["montoiva"]/100));
                $precio2 = $listproducto['precio2'] * (1+($_SESSION["montoiva"]/100));
                $precio3 = $listproducto['precio3'] * (1+($_SESSION["montoiva"]/100));
            }

            ?>
            <form action="inicioUsuario.php?buscarproducto=<? echo $buscarproducto?>&updateprod=<? echo $codprodmodificar?>&verunico=1&sinmenu=<? echo $sinmenu ?>" name="form3" id="form3" method="post">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="93%" align="left">Descripci&oacute;n &nbsp;&nbsp;<span class="nombrecampo" >
                <input name="descripprod"  class="inputdata" type="text" value='<? echo utf8_encode($producto);?>'  maxlength="<? echo $productMaxLength?>"  required  style=" width:60%">
              </span></td>
                    </tr>
                </table>

                <div class="tab_container" style="width:100%">
                    <? $fecha=getFechaHoy();
                    if($tab=='tab1'){
                        if($buscarproducto=='undefined')$buscarproducto=$codprod;
                        ?>
                        <div id="tab1" class="tab_content" <? echo ' style="height:360px; overflow:auto"'?>>
                            <table width="100%" border="0">
                                <tr>
                                    <td align="left" width="10%">Descripci&oacute;n2
                                        <input type="hidden" class="inputdata" name="sinmenu" id="sinmenu" value="<? echo $sinmenu;?>" />                        </td>
                                    <td  align="left"width="33%">
                                        <input name="descrip2prod" class="inputdata" type="text" value="<? echo utf8_encode($producto2);   ?>" maxlength="<? echo $productMaxLength?>" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>                 </td>
                                    <td width="13%"   align="left">&nbsp;&nbsp;&nbsp;Referencia</td>
                                    <td width="44%"colspan="3"  align="left" >
                                        <input name="refereprod"class="inputdata"  type="text" value="<? echo utf8_encode($refere);  ?>" maxlength="<? echo $productMaxLength?>" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>               </td>
                                </tr>
                                <tr>
                                    <td align="left">Descripci&oacute;n3</td>
                                    <td align="left">
                                        <input name="descrip3prod" class="inputdata" type="text" value="<? echo  utf8_encode($producto3);?>" maxlength="<? echo $productMaxLength?>" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>               </td>
                                    <td   align="left" >&nbsp;&nbsp;&nbsp;Marca</td>
                                    <td  align="left"colspan="3" >
                                        <input name="marcaprod" class="inputdata" type="text" value="<? echo utf8_encode($marca);?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>                </td>
                                </tr>
                                <tr>
                                    <td align="left">Descripci&oacute;n4</td>
                                    <td align="left">
                                        <input name="descrip4prod" class="inputdata" type="text" value="<? echo utf8_encode($producto4);  ?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>                </td>
                                    <td   align="left">&nbsp;&nbsp;&nbsp;Fecha &Uacute;ltima Venta                  </td>
                                    <td   align="left"colspan="3"  >
                                        <input name="fechauv" class="inputdata" type="text" value="<? echo $fechauv;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/>                 </td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="2">
                                        <table width="100%">
                                            <tr>
                                                <td width="22%" align="left">Es Exento?</td>
                                                <td width="27%" align="left">   <input type="checkbox" name="prodexento" id="prodexento" value="1" <? if($prodexento){?> checked <? }?>> </td>
                                                <td width="29%" align="right"><? if(!$desseri){?> Exist Decimal? <? }?></td>
                                                <td width="22%" align="left">
                                                    <? if(!$desseri){?>
                                                        <input  type="checkbox" name="ExDecimal" id="ExDecimal" value="1" <? if($ExDecimal){?> checked <? }?>>
                                                    <? }?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="left">Es al Mayor?</td>
                                                <td width="27%" align="left"> <input type="checkbox" name="prodmayor" id="prodmayor" value="1" <? if($prodmayor){?> checked <? }?>></td>
                                                <td align="right" >  <? if($precioenpesos){?>Precio en Dolares Fijo   <? }?> </td>
                                                <td align="left">  <? if($precioenpesos){?><input  type="checkbox" name="preciodolarfijo" id="preciodolarfijo" value="1" <? if($preciodolarfijo == 1){?> checked <? }?>>  <? }?></td>
                                            </tr>

                                        </table>                        </td>
                                    <td colspan="2">
                                        <table width="100%">
                                            <tr>
                                                <td  align="left"width="15%">&nbsp;&nbsp;&nbsp;Peso</td>
                                                <td  align="left"width="35%"><input name="pesoprod" class="inputdata" type="text" value="<? echo $pesoprod+0;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/></td>
                                                <td  align="center"width="18%">Unidad Medida</td>
                                                <td  align="left"width="32%"><input name="unidadprod"class="inputdata"  type="text" value="<? echo $unidadprod;?>" maxlength="40" style=" width:95%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))"/></td>
                                            </tr>
                                            <? if($manejoCajasPzas == 1){?>
                                                <tr>
                                                    <td  align="left">&nbsp;&nbsp;&nbsp;Mts Caja</td>
                                                    <td  align="left"><input name="mtscaja" class="inputdata" type="text" value="<? echo $mtscaja;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/></td>
                                                    <td  align="center">Mts Pzas</td>
                                                    <td  align="left"><input name="mtspieza" class="inputdata" type="text" value="<? echo $mtspieza;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/></td>
                                                </tr>
                                            <? }?>
                                        </table>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="2">

                                        <table width="100%">
                                            <tr>
                                                <td width="22%" align="left">Est&aacute; Publicado?</td>
                                                <td width="28%" align="left">
                                                    <input type="checkbox" name="prodpublicado" id="prodpublicado" value="1" <? if($prodpublicado){?> checked <? }?>>                                    </td>
                                                <td width="28%" align="right">Activo?</td>
                                                <td width="22%" align="left">
                                                    <input type="checkbox" name="prodactivo" id="prodactivo" value="1" <? if($prodactivo==1){?> checked <? }?>>                                    </td>
                                            </tr>
                                        </table>                         </td>
                                    <td  align="left">&nbsp;&nbsp;&nbsp;Volumen</td>
                                    <td  align="left"colspan="3"><input name="volumenprod" class="inputdata" type="text" value="<? echo $volumenprod+0;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/></td>
                                </tr>
                                <tr>
                                    <td align="left" colspan="2"><table width="100%">
                                            <tr>
                                                <td width="22%" align="left">Es a Consignaci&oacute;n?</td>
                                                <td width="28%" align="left"><input type="checkbox" name="consignacion" id="consignacion" value="1" <? if($consignacion){?> checked <? }?>></td>
                                                <td width="28%" align="right"><? if($prodcontrola){?>Prod Controlado? <? }?></td>
                                                <td width="22%" align="left"><? if($prodcontrola){?>
                                                        <input type="checkbox" name="controlado" id="controlado" value="1" <? if($controlado){?> checked <? }?>> <? }?></td>
                                            </tr>
                                        </table></td>
                                    <td  align="left"  >&nbsp;&nbsp;&nbsp;Cant x Caja</td>
                                    <td  align="left"colspan="3" ><input name="cantxempaq" class="inputdata" type="text" value="<? echo $cantxempaq+0;?>" maxlength="40" style=" width:98%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/></td>
                                </tr>
                                <tr>
                                    <td align="left"><input type="submit" name="Submit2" class="boton"  value="Enviar" onChange="submit3_pag('inicioUsuario.php?sinmenu=<? echo $sinmenu ?>')" /></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td colspan="3"  class="titulo" >Instancia Inv: <? echo $instancia ?> </td>
                                </tr>
                            </table>

                            <? if(!$DEsComp){?>

                                <div align="left" style="width:100%;  ">
                                    <?
                                    $sql=" SELECT CodAlte, CodInst, InsPadre, REPLICATE('&nbsp;', (Nivel - 1) * 4) + ' | '+ Descrip AS Descrip, nivel, (select count(*) as tantos from $companydb.dbo.saprod where codinst = a.codinst) as tantos
                                                      FROM $companydb.dbo.sainsta a WITH (NOLOCK)
                                                      WHERE a.TipoIns = 0  	
                                                      ORDER BY a.CodAlte,  a.Descrip
                                                    ";

                                    $res=mssql_query($sql,$linkms)or die(mssql_get_last_message());
                                    while($lista=mssql_fetch_array($res)){
                                        if($lista['InsPadre']==0){
                                            $tienehijos=0;
                                            $alterno=$lista['CodAlte'];
                                            $inspadre=$alterno=$lista['CodInst'];
                                            $sqlins=" SELECT CodAlte
                                                                  FROM $companydb.dbo.sainsta 
                                                                  WHERE InsPadre = $inspadre 	 
                                                                ";

                                            $resins=mssql_query($sqlins,$linkms)or die(mssql_get_last_message());
                                            if($listains=mssql_fetch_array($resins)){

                                                $tienehijos=1;
                                            }
                                        }
                                        ?>
                                        <div <? if($lista['InsPadre']==0){ ?> onClick="$('.hijo').hide(); $('.<? echo $alterno?>').fadeIn()" <? }else{?> class="hijo <? echo $alterno.''?>"<? }?> style="display:inline-block; text-align:left; padding:5px; width:580px; <? if($lista['InsPadre']==0){ echo ' height:16px;';?> cursor:pointer; color:#FFFFFF;<? }else{?>   <? if($Codpadre!=$alterno){?> <? //display:none

                                        } }?>" <? if($lista['InsPadre']==0){ echo 'class="color20"'; }?>>
                                            <table width="100%" border="0" >
                                                <tr>
                                                    <td width="79%"><? if($lista['InsPadre']==0){
                                                            if($tienehijos==1){?>
                                                                <span style="color:#FFFFFF" > <a  onclick="confirmarCambioInstancia('<? echo $codprodmodificar?>','<? echo str_replace("'","",$lista['Descrip'])?>',<? echo $lista['CodInst']?>)"  href="#" style="color:#FFFFFF" name="<? echo $lista['CodInst']?>"  ><? echo $lista['Descrip']; ?></a></span>
                                                            <? }else{?>
                                                                <a onClick="confirmarCambioInstancia('<? echo str_replace("'","",$codprodmodificar)?>','<? echo str_replace("'","",$lista['Descrip'])?>', <? echo $lista['CodInst']?>)"  href="#" style="color:#FFFFFF" ><? echo $lista['Descrip']?></a>
                                                                <?
                                                            }
                                                        }else{?>
                                                            <a onClick="confirmarCambioInstancia('<? echo str_replace("'","",$codprodmodificar)?>','<? echo str_replace("'","",$lista['Descrip'])?>' ,<? echo $lista['CodInst']?>)"
                                                               href="#"><? echo $lista['Descrip']?></a>
                                                        <? }

                                                        ?></td>
                                                    <td width="13%" align="right"> </td>
                                                    <td width="8%" align="right"><a href="verInventario.php?CodInst=<? echo $lista['CodInst']?>" style="display:block; width:99%;  <? if($lista['InsPadre']==0){ echo 'color:#FFFFFF;'; }?>"  target="_blank">
                                                            <?   echo $lista['CodInst']//$lista['tantos'];  ?>
                                                        </a> </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <?
                                    }//
                                    ?>
                                </div>

                            <? }?>
                        </div>
                    <? }   ?>
                </div>

            </form>
            <?


        }

        if($codservdmodificar!=''  ){
            $dataprepesos = "";
            if($precioenpesos)
                $dataprepesos = ", prepesos3 , preciodolarfijo";


            $sql="SELECT  a.costod, a.esexento, a.descrip, a.usaserv, a.EsDecimal, a.preciod  $dataprepesos
							FROM $companydb.dbo.saserv as a 
							WHERE a.codserv='$codservdmodificar' 
						  ";

            $res=mssql_query($sql,$linkms);
            $listserv=mssql_fetch_array($res);

            $precioserv		= $listserv['costod'];
            $prepesos3      = $listserv['prepesos3'];
            $costoserv      = $listserv['preciod'];
            $EsDecimal		= $listserv['EsDecimal'];
            $preciodolarfijo= $listserv['preciodolarfijo'];
            $esexento		= $listserv['esexento'];
            $servexento     = $esexento;
            $descripserv	= $listserv['descrip'];
            $esexento		= $listserv['esexento'];
            $usaserv		= $listserv['usaserv'];


            if($esexento==1){}else{
                //	$precioserv = $listserv['costod'] * (1+($_SESSION["montoiva"]/100));
            }

            ?>
            <form action="inicioUsuario.php?buscarproducto=<? echo $codservdmodificar?>&updateserv=<? echo $codservdmodificar?>&verunico=1&sinmenu=<? echo $sinmenu ?>" name="form3" id="form3" method="post">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                    <tr>
                        <td width="93%" align="left"class="nombrecampo" >Descripci&oacute;n del servicio &nbsp;&nbsp;
                            <input name="descripserv" type="text" value='<? echo  $descripserv;?>' onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,9))" class="inputdata" maxlength="40" style=" width:60%">
                        </td>
                    </tr>
                </table>

                <div class="tab_container" style="width:100%">
                    <? $fecha=getFechaHoy();
                    if($tab=='tab1'){
                    if($buscarproducto=='undefined')$buscarproducto=$codservdmodificar;
                    ?>
                    <div id="tab1" class="tab_content" <? echo ' style="height:360px; overflow:auto"'?>>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0"   >
                            <tr>
                                <td  align="left"width="72%" class="nombrecampo"   >

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"   >
                                        <tr>
                                            <td width="13%" height="30" align="left">Es Exento?</td>
                                            <td  align="left"width="36%" class="nombrecampo" >
                                                <input type="checkbox" name="servexento" id="checkbox" value="1" <? if($servexento){?> checked <? }?>>                      </td>
                                            <td  align="left"width="11%" class="nombrecampo" >Usa Servidor?</td>
                                            <td  align="left"width="40%" class="nombrecampo" >
                                                <input type="checkbox" name="usaserv" id="checkbox" value="1" <? if($usaserv){?> checked <? }?>>                      </td>
                                        </tr>
                                        <tr>
                                            <td align="left"width="13%"  >Precio Servicio                        </td>
                                            <td align="left"width="36%"   >
                                                <?
                                                if($precioenpesos){
                                                    if($preciodolarfijo == 1){?>

                                                        <input name="precioserv" type="text" value="<? echo $precioserv; ?>" class="inputdata" maxlength="40" style=" width:60%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/>
                                                        $
                                                    <? }else{?>

                                                        <input name="prepesos3" type="text" value="<? echo number_format($prepesos3,0,',',''); ?>" class="inputdata" maxlength="40" style=" width:60%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/>
                                                        COP
                                                    <? }
                                                }else{?>
                                                    <input name="precioserv" type="text" value="<? echo $precioserv; ?>" class="inputdata" maxlength="40" style=" width:60%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/>
                                                    $
                                                    <?
                                                }
                                                ?>
                                            </td>
                                            <td align="left"width="11%"   >Es Decimal?</td>
                                            <td align="left"width="40%"   ><input type="checkbox" name="EsDecimal" id="EsDecimal" value="1" <? if($EsDecimal){?> checked <? }?>></td>
                                        </tr>
                                        <tr>
                                            <td align="left"width="13%"  >Costo Servicio                        </td>
                                            <td align="left"width="36%"   >
<span  >
<input name="costoserv" type="text" value="<? echo $costoserv; ?>" class="inputdata" maxlength="40" style=" width:60%" onKeyPress="return(tabular(event,this) ||  formato_campo(this,event,5))"/>
</span>                        $</td>
                                            <td align="left"width="11%"   > <? if( $precioenpesos){?> Precio en Dolares Fijo <? }?></td>
                                            <td align="left"width="40%"   >
                                                <? if( $precioenpesos){?>
                                                    <input type="checkbox" name="preciodolarfijo" id="preciodolarfijo" value="1" <? if($preciodolarfijo == 1){?> checked <? }?>>
                                                <? }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left"><input type="submit" name="Submit" class="boton"  value="Enviar" onChange="submit3_pag('inicioUsuario.php?buscarproducto=<? echo $codservdmodificar?>&updateserv=<? echo $codservdmodificar?>&verunico=1&sinmenu=<? echo $sinmenu ?>')" />                      </td>
                                            <td  align="left" class="titulo " >&nbsp;</td>
                                            <td  align="left" class="titulo " >&nbsp;</td>
                                            <td  align="left" class="titulo " >&nbsp;</td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
                <? }   ?>
            </form>

        <? }
        ?>

    </div>
    <?

    if (($_SESSION['i'] and !$sinmenu) or $operacioncargodescargo !='') {
        ?>

        <div class="row">
            <div class="col-md-10" onClick="$('#btnactualizar').hide();">
                <form name="formcant" id="formcant" action="inicioUsuario.php?sinmenu=<? echo $sinmenu ?>">

                    <div >


                        <div style=" display:flex; flex-direction:column-reverse; margin-bottom:20px;">


                            <?
                            $fielprodbs = "";
                            if($productosbs == 1){
                                $fielprodbs = ", a.precio3, a.prodbs ";
                            }


                            $datafind = '';
                            if(isset($prodcolor) and $prodcolor == 1)
                                $datafind = "  , a.color   ";

                            for($ii=1; $ii < $_SESSION['i']; $ii++){

                                $sql='';


                                $ubicacion = $_SESSION['codubic'][$ii];



                                if($_SESSION['codserv'][$ii]==1){


                                    $sql="SELECT a.descrip as producto, a.codserv as codprod , a.precio1, a.descrip2 as descrip2,
                                                    a.descrip3 as descrip3, costo, a.esexento, a.costod, a.EsDecimal
                                                    FROM $companydb.dbo.saserv as a
                                                    WHERE  a.codserv='".$_SESSION['codprod'][$ii]."'";


                                }else{

                                    $fiscal   = "saprod";
                                    $funcprod = "verProducto";
                                    $fiscped  = "1";
                                    $saexis   = "saexis";

                                    if(isset($_SESSION['fiscal'][$ii]) and $_SESSION['fiscal'][$ii] == 0){
                                        $fiscal   = "newsaprod";
                                        $funcprod = "verNewProducto";
                                        $saexis   = "newsaexis";
                                        $fiscped  = "0";
                                    }



                                    $sql="SELECT b.desseri , a.descrip as producto, b.descrip as instancia, a.refere, a.existen, a.codprod, a.DEsComp, a.marca, a.codinst,
                                                     a.esexento, a.costod3, a.costod, a.costod2, a.ExDecimal, cc.CantPed as cantidadped, cc.existen as existendepo $fielprodbs   $datafind 
													 
                                                     FROM $companydb.dbo.$fiscal as a, $companydb.dbo.SAINSTA as b, $companydb.dbo.$saexis cc
                                                     WHERE b.codinst=a.codinst
                                                     and a.CodProd  = '".$_SESSION['codprod'][$ii]."'
													 and a.CodProd  = cc.codprod
													 and a.DEsComp =0
													 and cc.codubic = '".$_SESSION['codubic'][$ii]."'  
													 
													 
													 union
													 
													 
													 SELECT b.desseri , a.descrip as producto, b.descrip as instancia, a.refere, a.existen, a.codprod, a.DEsComp, a.marca, a.codinst,
                                                     a.esexento, a.costod3, a.costod, a.costod2, a.ExDecimal , '0' as cantidadped, '0' as existendepo $fielprodbs   $datafind 
													 
                                                     FROM $companydb.dbo.$fiscal as a, $companydb.dbo.SAINSTA as b 
                                                     WHERE b.codinst=a.codinst
                                                     and a.CodProd  = '".$_SESSION['codprod'][$ii]."' 
													 and a.DEsComp  =1 
													  
													 
													 ";


                                }

                                if($sql!=''){


                                    $res=mssql_query($sql,$linkms)or die(mssql_get_last_message());

                                    if($lista = mssql_fetch_array($res)){
                                        $numeritoprd++;

                                        $prodbs     = $lista['prodbs'];
                                        if(!$prodbs and strpos($_SESSION['opciones'],",2124")>0)
                                            $noavanzafac = 1;
                                        $precio3    = $lista['precio3'];
                                        $desseri    = $lista['desseri'];
                                        $esexento   = $lista['esexento'];
                                        $DEsComp    = $lista['DEsComp'];
                                        $ExDecimal  = $lista['ExDecimal'];
                                        $codinstlst = $lista['codinst'];

                                        $montoiva = 0 ;


                                        $codubicii       = $_SESSION['codubic'][$ii];

                                        $cwpromocheckaux = (isset($_SESSION['cwpromocheck']))? $_SESSION['cwpromocheck'] : [];

                                        foreach($cwpromocheckaux as $index => $vectori){

                                            if(isset($_SESSION['cwpromocheck']) and $vectori['codinst']  > 0  and $codinstlst == $vectori['codinst']){
                                                $promocantidad += $_SESSION['cantidad'][$ii] ;
                                                $instapromo = $vectori['codinst'];
                                            }
                                        }

                                        ?>
                                        <table width="100%" border="0" align="center" class="  mobiletext "  style="  font-size:14px; color:#555333">
                                            <tr  <? if(($numeritoprd%2)==0){?> bgcolor="#eee"<? }?>>


                                                <td width="37%"align="left"class="tdlinebottom " style="cursor:pointer; " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')" >
                                                    <?
                                                    // echo $_SESSION['fiscal'][$ii].'< ';
                                                    if(!$_SESSION['codservprod'][$ii])
                                                        $_SESSION['codservprod'][$ii] =  $lista['codprod'];
                                                    ?>

                                                    <input name="prodcod[]" type="hidden" value="<? echo $lista['codprod']; ?>">

                                                    <? if($_SESSION['codserv'][$ii]==1){?>
                                                        <input value="<?  echo $_SESSION['codservprod'][$ii] ?>" size="2"
                                                               data-ii="<? echo $ii?>"
                                                               data-name="codservprod"
                                                               class="editservcod"
                                                               onKeyPress="return(tabular(event,this) || formato_campo(this,event,9))"
                                                               style="text-align:center !important;" onFocus="$(this).select()" />
                                                        <?


                                                    }else{?>

                                                        <div style="height:16px; overflow:hidden; width:100%;  font-size:10px;">
                                                            <?
                                                            if($codCompleto)
                                                                echo $lista['codprod'];
                                                            else
                                                                echo substr($lista['codprod'],-4,4);
                                                            echo 	' &nbsp;'.utf8_encode($lista['marca']);
                                                            ?>                                                    </div>
                                                    <? }
                                                    ?>
                                                    <?

                                                    if(!$_SESSION['descripserv'][$ii])
                                                        $_SESSION['descripserv'][$ii] =  utf8_encode($lista['producto']);

                                                    if($_SESSION['codserv'][$ii]==1 or $editableDescripInic){?>
                                                        <input value="<?  echo utf8_encode($_SESSION['descripserv'][$ii]) ?>" size="2"
                                                               data-ii="<? echo $ii?>"
                                                               data-name="descripserv"
                                                               class="editservcod"
                                                               onKeyPress="return(tabular(event,this) || formato_campo(this,event,9))"
                                                               style="text-align:left !important; width:98% " onFocus="$(this).select()" />
                                                    <? }else
                                                        echo  utf8_encode($lista['producto']);

                                                    if(isset($prodcolor) and $prodcolor == 1)
                                                        echo   utf8_encode($lista['color']);

                                                    $cantseriales = 0;
                                                    if(isset($_SESSION['NroSerial'][$lista['codprod']][$ii])){
                                                        echo '<br> ';
                                                        $cantseriales++;
                                                        foreach($_SESSION['NroSerial'][$lista['codprod']][$ii] as $serialitem){
                                                            echo  "  [". $serialitem." "; ?>
                                                            <a href="inicioUsuario.php?removeserial=1&serialr=<? echo $serialitem?>&iiserial=<? echo $ii?>&codprodserial=<? echo $lista['codprod']?>" style="color:red"> XX </a>
                                                            <? echo "] - ";
                                                        }
                                                    }
                                                    echo '<br> ';
                                                    ?>                                                  </td>
                                                <td width="7%"align="center"class="tdlinebottom"style="cursor:pointer"   >

                                                    <?

                                                    if(isset($_SESSION['partes'][$ii])){

                                                        if($DEsComp){
                                                            ?>
                                                            <a href="javascript:;"
                                                               onClick="$('.compparte<? echo $ii?>').val('')"
                                                               data-title="Agregar"
                                                               data-toggle="modal"
                                                               data-target="#coompmodal<? echo $ii?>"  style="border:none"><img  src="imagenes/updatesmall.png" /></a>
                                                            </a>
                                                            <div class="modal fade" id="coompmodal<? echo $ii?>" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true"  style="display:none"   >
                                                                <div class="modal-dialog modal-lg" style="width: 400px; margin:100px auto">
                                                                    <table width="400px"  border="0"  style="border:1px solid #FF8F32; margin:auto; background:#FFFFFF  " class="tdline"    >
                                                                        <tr>
                                                                            <td height="50" colspan="2" align="center" style="font-size:20px">PARTES PRODUCTO COMPUESTO  (<? echo  $_SESSION['maxcomp'][$ii]?>)</td>
                                                                        </tr>
                                                                        <?
                                                                        ///  SE.CODALTE, SD.DESCRIP, SE.CANTIDAD, SE.ESUNID, SE.ESSERV, SE.NroUnico, SD.EXISTEN
                                                                        $checpartes = 0;
                                                                        if(isset($_SESSION['partes'][$ii]))
                                                                        foreach($_SESSION['partes'][$ii] as $index => $parte){

                                                                        $checpartes += $parte['CANTIDAD'];

                                                                        ?>
                                                                        <tr <? if(($index%2)==0) echo 'bgcolor="#eee"'?> >
                                                                            <td width="84%" align="left" height="30"> <? echo $parte['DESCRIP']?> </td>
                                                                            <td width="16%" align="center">
                                                                                <input value  = "<?  echo $parte['CANTIDAD']+0 ?>" size="2"
                                                                                       data-iicomp   = "<? echo $ii?>"
                                                                                       data-codalte  = "<? echo $parte['CODALTE']?>"
                                                                                       data-maxcomp  = "<? echo  $_SESSION['maxcomp'][$ii]?>"
                                                                                       placeholder   = "<?  echo $parte['CANTIDAD']+0 ?>"
                                                                                       onKeyPress    = "return(tabular(event,this))"
                                                                                       class         = "inputdescomp  compparte<? echo $ii?>"
                                                                                       style         = "text-align:center !important; width:40px; border:none; background:transparent" onFocus="$(this).select()" />                                                                                                </td>

                                                                            <?   }

                                                                            if($checpartes != $_SESSION['maxcomp'][$ii]){
                                                                                ?>
                                                                                <script>   $('.botonirafacturar').hide()  </script>
                                                                                <?
                                                                            }  ?>
                                                                    </table>
                                                                    <div style="width:0px; height:0px; overflow:hidden; opacity:0">
                                                                        <input value="" size="2"  type="text"  width="0" height="0"
                                                                               style="width:0px; height:0px; opacity:0"  />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?  //
                                                        }
                                                    }else{

                                                        if(!$_SESSION['codserv'][$ii])
                                                            echo $_SESSION['codubic'][$ii];
                                                        else
                                                            echo 'Serv';
                                                    }
                                                    ?>

                                                </td>
                                                <td width="5%"align="right"class="tdlinebottom" >

                                                    <?

                                                    if($productosbs == 1 and $prodbs == 1){

                                                        if($_SESSION['precioprod'][$ii] > 0){

                                                        }else {

                                                            $acordado= $lista['costod3']*(1+($porcIncrementa/100));
                                                            $_SESSION['precioprod'][$ii] = $acordado;

                                                        }

                                                        $auxprecionoeditable= $precionoeditable;
                                                        if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                            $auxprecionoeditable=0;


                                                        if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                            ?>
                                                            <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar"  style="background:unset" >
                                                                <?
                                                                echo "Bs. ".number_format($_SESSION['precioprod'][$ii]* $tasaoficial,2,',','.');
                                                                $precioline   = $_SESSION['precioprod'][$ii] * $_SESSION['cantidad'][$ii];
                                                                $preciolinebs = $_SESSION['precioprod'][$ii] * $_SESSION['cantidad'][$ii] * $tasaoficial;
                                                                $preciot   += $_SESSION['precioprod'][$ii]*$_SESSION['cantidad'][$ii];
                                                                $preciottt += $_SESSION['precioprod'][$ii]*$_SESSION['cantidad'][$ii];
                                                                $precio     = $_SESSION['precioprod'][$ii];
                                                                ?>
                                                            </a>
                                                            <?
                                                        }

                                                    }else{

                                                        $varprecio      = 'costod3';
                                                        $varpreciop     = 'prepesos3';
                                                        if($precioesta >0){
                                                            if($precioesta == 1){
                                                                $varprecio      = 'costod';
                                                                $varpreciop     = 'prepesos1';
                                                            }
                                                            if($precioesta == 2){
                                                                $varprecio      = 'costod2';
                                                                $varpreciop     = 'prepesos2';
                                                            }

                                                        }

                                                        if($preciodefecto >0){

                                                            if($preciodefecto == 1){
                                                                $varprecio      = 'costod';
                                                                $varpreciop     = 'prepesos1';
                                                                $_SESSION['precio123'] = 1;
                                                            }
                                                            if($preciodefecto == 2){
                                                                $varprecio      = 'costod2';
                                                                $varpreciop     = 'prepesos2';
                                                                $_SESSION['precio123'] = 2;
                                                            }
                                                            if($preciodefecto == 3){
                                                                $varprecio      = 'costod3';
                                                                $varpreciop     = 'prepesos3';
                                                                $_SESSION['precio123'] = 3;
                                                            }

                                                        }else{

                                                            if($_SESSION['precio123'] == 1){
                                                                $varprecio      = 'costod';
                                                                $varpreciop     = 'prepesos1';
                                                            }
                                                            if($_SESSION['precio123'] == 2){
                                                                $varprecio      = 'costod2';
                                                                $varpreciop     = 'prepesos2';
                                                            }
                                                            if($_SESSION['precio123'] == 3){
                                                                $varprecio      = 'costod3';
                                                                $varpreciop     = 'prepesos3';
                                                            }

                                                        }



                                                        if($_SESSION['bonificado'][$ii]){
                                                            $cantbonificado += $_SESSION['cantidad'][$ii];
                                                        }else{

                                                            $acordado=0;

                                                            $faco = 1+(16/100);
                                                            if($esexento == 1)
                                                                $faco = 1;

                                                            if($_SESSION['precioprod'][$ii]>0){

                                                                if($_SESSION['codserv'][$ii]==1){
                                                                    $acordado = $_SESSION['precioprod'][$ii];

                                                                    if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                        $acordado =  number_format($acordado/$faco,4,'.','');
                                                                    }

                                                                    $auxprecionoeditable= $precionoeditable;
                                                                    if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                        $auxprecionoeditable=0;


                                                                    if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                        //and $soloverdes == 1){?>
                                                                        <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar"  style="background:unset" >
                                                                            <? echo number_format($acordado,$decimalesPrecio,',','.');?> </a>
                                                                    <? }else
                                                                        echo number_format($acordado,$decimalesPrecio,',','.');

                                                                    $preciot+=$acordado*$_SESSION['cantidad'][$ii];
                                                                    $preciottt+=number_format($acordado*$_SESSION['cantidad'][$ii],$decimalesPrecio,'.','');

                                                                    $precio=$acordado;
                                                                }else{
                                                                    $acordado = $_SESSION['precioprod'][$ii];

                                                                    if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                        $acordado =  number_format($acordado/$faco,$decimalesPrecio,'.','');
                                                                    }


                                                                    if($_SESSION['company_row']['preciobase'] == 0){

                                                                        $auxprecionoeditable= $precionoeditable;
                                                                        if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                            $auxprecionoeditable=0;


                                                                        if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                            ?>
                                                                            <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar" style="background:unset"  ><?  echo number_format($acordado,$decimalesPrecio,',','.');?> </a>
                                                                            <?
                                                                        }else
                                                                            echo number_format($acordado,$decimalesPrecio,',','.');
                                                                        $preciottt+=number_format($acordado*$_SESSION['cantidad'][$ii],$decimalesPrecio,'.','');

                                                                        $preciot += $acordado*$_SESSION['cantidad'][$ii];
                                                                        $precio   = $acordado;
                                                                    }else{
                                                                        $montoiva = 0;
                                                                        $echoprecio = $acordado;


                                                                        if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                            //$echoprecio =  number_format($echoprecio,$decimalesPrecio,'.','');
                                                                        }

                                                                        $auxprecionoeditable= $precionoeditable;
                                                                        if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                            $auxprecionoeditable=0;


                                                                        if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                            ?>
                                                                            <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar" style="background:unset">
                                                                                <? echo number_format($echoprecio,$decimalesPrecio,',','.');?>                                                    </a>
                                                                            <?
                                                                        }else{
                                                                            echo number_format($echoprecio,$decimalesPrecio,',','.');
                                                                        }

                                                                        $preciot += $echoprecio*$_SESSION['cantidad'][$ii];
                                                                        $preciottt+=number_format($echoprecio*$_SESSION['cantidad'][$ii],$decimalesPrecio,'.','');
                                                                        $precio   = $echoprecio;
                                                                    }


                                                                }

                                                            }else{

                                                                if($_SESSION['codserv'][$ii]==1){
                                                                    $acordado = precioacordadoserv($lista['codprod'],$linkms,$link);
                                                                    $_SESSION['precioprod'][$ii] = $acordado * (1+($porcIncrementa/100));

                                                                    $acordado = $_SESSION['precioprod'][$ii];

                                                                    if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                        $acordado =  number_format($acordado/$faco,$decimalesPrecio,'.','');
                                                                    }

                                                                    $auxprecionoeditable= $precionoeditable;
                                                                    if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                        $auxprecionoeditable=0;



                                                                    if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                        ?>
                                                                        <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar"  style="background:unset"><? echo number_format($acordado,$decimalesPrecio,',','.');?></a>
                                                                        <?
                                                                    }else
                                                                        echo number_format($acordado,$decimalesPrecio,',','.');
                                                                }else{


                                                                    $acordado= $lista[$varprecio] * (1+($porcIncrementa/100));

                                                                    $_SESSION['precioprod'][$ii] = $acordado ;

                                                                    /*if($idsession == 201)
                                                                    echo $acordado;*/

                                                                    if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                        $acordado =  number_format($acordado/$faco,$decimalesPrecio,'.','');
                                                                    }


                                                                    $auxprecionoeditable= $precionoeditable;
                                                                    if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                        $auxprecionoeditable=0;


                                                                    if($_SESSION['company_row']['preciobase'] == 0){
                                                                        if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                            ?>
                                                                            <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar"  style="background:unset"><? echo number_format($acordado,$decimalesPrecio,',','.');?> </a>
                                                                            <?
                                                                        }else
                                                                            echo number_format($acordado,$decimalesPrecio,',','.');

                                                                        $preciot+=$acordado*$_SESSION['cantidad'][$ii];
                                                                        $preciottt+=number_format($acordado*$_SESSION['cantidad'][$ii],$decimalesPrecio,'.','');
                                                                        $precio=$acordado;
                                                                    }else{
                                                                        $montoiva = 0;
                                                                        $echoprecio = $acordado;


                                                                        $_SESSION['precioprod'][$ii] = $lista[$varprecio];

                                                                        if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                            //  $echoprecio =  number_format($echoprecio/$faco,$decimalesPrecio,'.','');
                                                                        }

                                                                        $auxprecionoeditable= $precionoeditable;
                                                                        if($_SESSION['codserv'][$ii]==1 or strpos($_SESSION['opciones'],",1334")>0)
                                                                            $auxprecionoeditable=0;

                                                                        if((strpos($_SESSION['opciones'],",57")>0 or $_SESSION['codserv'][$ii]==1 ) and   $auxprecionoeditable ==0){
                                                                            ?>
                                                                            <a href="cambiarprecioprod.php?ii=<? echo $ii;?>&codprod=<? echo urlencode($_SESSION['codprod'][$ii])?>" class="ventanalocal linkcambiar"  style="background:unset"><? echo number_format($echoprecio,$decimalesPrecio,',','.');?></a>
                                                                            <?
                                                                        }
                                                                        else
                                                                            echo number_format($echoprecio,$decimalesPrecio,',','.');

                                                                        $preciot += $echoprecio*$_SESSION['cantidad'][$ii];
                                                                        $preciottt+=number_format($echoprecio*$_SESSION['cantidad'][$ii],$decimalesPrecio,'.','');

                                                                        $precio   = $echoprecio;
                                                                    }
                                                                }


                                                            }

                                                            if(($_SESSION['notasiniva'] or $_SESSION['company_row']['preciobase'] == 1) and !$esexento){
                                                                $precioline   = $_SESSION['precioprod'][$ii]/$faco * $_SESSION['cantidad'][$ii];

                                                            }else{
                                                                $precioline   = $_SESSION['precioprod'][$ii] * $_SESSION['cantidad'][$ii];
                                                            }


                                                            $preciolinebs = number_format($_SESSION['precioprod'][$ii],$decimalesPrecio,'.','') * $_SESSION['cantidad'][$ii] * $factorcambio;

                                                        }


                                                    }
                                                    ?>												  </td>
                                                <td width="7%" align="center"class="tdlinebottom">
                                                    <?

                                                    $codproditem = $lista['codprod'];

                                                    $cantped = $lista['cantidadped'];


                                                    $prdexisten = $lista['existendepo'];
                                                    // if(strpos($_SESSION['opciones'],",40")>0 ){

                                                    $nn = (strpos($_SESSION['opciones'],",2116")>0 or strpos($_SESSION['opciones'],",591")>0)? 20000:  $prdexisten-$cantped;
                                                    if($_SESSION['bonificado'][$ii]){
                                                        echo $_SESSION['cantidad'][$ii];
                                                    }else{
                                                        if($desseri == 0){
                                                            $cantagregada   += $_SESSION['cantidad'][$ii];
                                                            ?>
                                                            <input value="<?  echo ($ExDecimal > 0)? ($_SESSION['cantidad'][$ii]+0) :  number_format($_SESSION['cantidad'][$ii],0,'','')  ;   ?>" size="2"
                                                                   data-ii   = "<? echo $ii?>"
                                                                   data-nn   = "<? echo ($DEsComp)? 20000 : $nn ?>"
                                                                <? echo ($DEsComp)? ' readonly ' : '' ?>
                                                                   data-ccc  = "<? echo ($DEsComp)? 1 : 0?>"
                                                                   data-serv = "<? echo ($_SESSION['codserv'][$ii])? $_SESSION['codserv'][$ii] : 0?>"
                                                                   class     = "enviarcantidad"
                                                                   onKeyPress= "return(tabular(event,this) || formato_campo(this,event,<? echo ($lista['ExDecimal']==1 or $lista['EsDecimal']==1)? '5': '1' ?>))"
                                                                   style     = "text-align:center !important; width:40px;" onFocus="$(this).select()" />


                                                            <input name="p<? echo $lista['codprod']; ?>" type="hidden" value="<? echo $_SESSION['cantidad'][$ii]; ?>">
                                                        <? }else{
                                                            if($cantseriales > 0){
                                                                echo number_format($_SESSION['cantidad'][$ii],0,'','') ;   $cantagregada   += $_SESSION['cantidad'][$ii];
                                                            }else{

                                                                if(  strpos($_SESSION['opciones'],",591")>0){
                                                                    ?>
                                                                    <input value="<?  echo ($ExDecimal > 0)? ($_SESSION['cantidad'][$ii]+0) :  number_format($_SESSION['cantidad'][$ii],0,'','')  ;   ?>" size="2"
                                                                           data-ii   = "<? echo $ii?>"
                                                                           data-nn   = "<? echo ($DEsComp)? 20000 : $nn ?>"
                                                                        <? echo ($DEsComp)? ' readonly ' : '' ?>
                                                                           data-ccc  = "<? echo ($DEsComp)? 1 : 0?>"
                                                                           data-serv = "<? echo ($_SESSION['codserv'][$ii])? $_SESSION['codserv'][$ii] : 0?>"
                                                                           class     = "enviarcantidad"
                                                                           onKeyPress= "return(tabular(event,this) || formato_campo(this,event,<? echo ($lista['ExDecimal']==1 or $lista['EsDecimal']==1)? '5': '1' ?>))"
                                                                           style     = "text-align:center !important; width:40px;" onFocus="$(this).select()" />


                                                                    <input name="p<? echo $lista['codprod']; ?>" type="hidden" value="<? echo $_SESSION['cantidad'][$ii]; ?>">
                                                                    <?
                                                                }
                                                            }
                                                        }

                                                    } //if bonificado

                                                    ?>
                                                </td>
                                                <td width="8%" align="center"class="tdlinebottom" style="cursor:pointer;  " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')">

                                                    <?


                                                    if($DEsComp){
                                                        echo 'Comp';
                                                        ?>
                                                        <script>   // $('.prodesDEsComp<? echo $lista['codprod']?>').fadeOut(); </script>
                                                        <?
                                                    }else{

                                                        if($_SESSION['codserv'][$ii]){

                                                        }else{
                                                            echo ($ExDecimal > 0)?  ($prdexisten+0) :  number_format($prdexisten,0,'','')  ;
                                                        }

                                                        if($prdexisten==0){  $nosugerido=1;}
                                                    }
                                                    ?>

                                                    <script>
                                                        $('.noencontrado<? echo str_replace('.','',$lista['codprod']).$_SESSION['fiscal'][$ii]?>').removeClass('noencontrado');
                                                    </script>

                                                </td>
                                                <td width="8%" align="right"class="tdlinebottom" style="cursor:pointer;  " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')">
                                                    <?

                                                    if($_SESSION['bonificado'][$ii]){

                                                    }else{


                                                        echo number_format($precioline,$decimalesPrecio,',','.');

                                                    }
                                                    ?>                                                              </td>

                                                <td width="11%" align="right"class="tdlinebottom" style="cursor:pointer;  " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')">
                                                    <?

                                                    if($_SESSION['bonificado'][$ii]){

                                                    }else{
                                                        echo($precioenpesos)?number_format($precioline*$pesoxdolar,0,',','.'):'';
                                                        $preciotpesos+= number_format($precioline*$pesoxdolar,0,'.','');
                                                    }
                                                    ?>                                                     </td>
                                                <td width="12%" align="right"class="tdlinebottom" style="cursor:pointer; text-align:right;  " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')">
                                                    <?
                                                    if($_SESSION['bonificado'][$ii]){

                                                    }else{


                                                        if($productosbs == 1 and $prodbs == 1){
                                                            $preciotttline +=  $preciolinebs ;
                                                            echo  number_format($preciolinebs,$decimalesPrecio,',','.');
                                                        }else{
                                                            $preciotttline += number_format($preciolinebs,$decimalesPrecio,'.','');
                                                            echo  number_format($preciolinebs,$decimalesPrecio,',','.');
                                                        }

                                                    }
                                                    ?>                                                     </td>

                                                <? if(!$pedidosdesactivados){?>
                                                    <td width="4%" align="center"class="tdlinebottom" style="cursor:pointer;  " onClick="<? echo $funcprod?>('<? echo $_SESSION['mydb'][$ii]?>','<? echo $lista['codprod']?>','','<? echo $buscarproducto?>', '<? echo $exiscero;?>', '<? echo $inactivos;?>', '<? echo $busqcodigo;?>')">
                                                        <?

                                                        if($_SESSION['bonificado'][$ii]){

                                                        }else{
                                                            if($cantped > 0)
                                                                echo ($ExDecimal > 0)? number_format($cantped,3,',','.') :  number_format($cantped,0,',','.')  ;
                                                        }
                                                        ?>                                                    </td>
                                                <? }?>
                                                <td width="1%" align="center"class="tdlinebottomfff">
                                                    <a href="inicioUsuario.php?sacardelalista=1&exiscero=<? echo $exiscero?>&inactivos=<? echo $inactivos?>&busqcodigo=<? echo $busqcodigo?>&codubic=<?  echo $_SESSION['codubic'][$ii]?>&codprod=<?  echo $_SESSION['codprod'][$ii]?>&buscarproducto=<? echo $buscarproducto?>&iisacar=<? echo $ii?>&sinmenu=<? echo $sinmenu ?>" style="color:#FF0000">X</a>												  </td>
                                            </tr>
                                        </table>

                                        <?

                                        $producto = utf8_encode($lista['producto']);
                                        $producto = str_replace("+"," ",$producto);
                                        $producto = str_replace("\"","",$producto);

                                        $refere   = $lista['refere'];
                                        $refere   = str_replace("+"," ",$refere);
                                        $refere   = str_replace("\"","",$refere);
                                    }else{

                                        /*if($_SESSION['codprod'][$ii] !='')
                                            die($_SESSION['codprod'][$ii].' failed');*/
                                    }
                                }
                            }  //for
                            ?>
                            <table width="100%" border="0" align="center" class=" mobiletext "  style="   color:#555333">
                                <tr>
                                    <td width="37%" height="46"align="center"class="tdline  " >
                                        <div style=" width:0px; height:0px; overflow:hidden; opacity:0">
                                            <input value="asd" size="1"   onKeyPress="" />
                                        </div>
                                        <? if($ocultarbstotal !=1 ){?>
                                        BS.<? echo number_format($preciotttline,$decimalesPrecio,',','.')?>
                                        <? }?>
                                        <? if(!$ocultarCOP){?>
                                            | COP.<?
                                            echo number_format($preciotpesos,0,',','.')?>
                                        <? }?> </td>
                                    <td align="center" class="tdline  " colspan="2">
                                        <div class="reload preciotvalue">
                                            USD $<? echo number_format($preciot, $decimalesPrecio, ',', '.')?>
                                        </div>
                                        <a href="inicioUsuario.php?sinmenu=<? echo $sinmenu ?>" class="link_reload" style="display:none;  ">Recargar Pag </a>                      </td>
                                    <td width="7%"align="center" class="tdline  "><div class="reload cantagregadavalue"> <? echo $cantagregada ?></div> </td>
                                    <td width="8%"align="center" class="tdline  ">EXISTENCIA</td>
                                    <td width="8%"align="center" class="tdline  ">USD*CANT</td>
                                    <td width="11%"align="center" class="tdline  "> <?  echo($precioenpesos)?'PESOS*CANT':'';  ?></td>

                                    <td width="12%"align="center" class="tdline  ">BS*CANT</td>
                                    <? if(!$pedidosdesactivados){?>
                                        <td width="4%"align="center" class="tdline  ">PED</td>
                                    <? }?>
                                    <td width="1%"align="center" >&nbsp;</td>
                                </tr>

                                <?

                                // and

                                $cwpromocheckaux = (isset($_SESSION['cwpromocheck']))? $_SESSION['cwpromocheck'] : [];

                                foreach($cwpromocheckaux as $vectori){

                                    if($vectori['codinst'] == $instapromo){
                                        if( isset($_SESSION['cwpromocheck']) and !$cantbonificado
                                            and  $vectori['codinst']  > 0  and $vectori['cantidad']>0
                                            and ($promocantidad >= $vectori['cantidad'] or $promocantidad >= $vectori['cantidad2'] )
                                        ){

                                            $cantidad1 = $promocantidad / $vectori['cantidad'];

                                            list($entp1,$decp1) = explode('.', $cantidad1);

                                            $cantidad2 =  $promocantidad -  ($entp1* $vectori['cantidad'] );

                                            $entp1 = $entp1 * $vectori['cantidad'] * $vectori['porc'];


                                            //echo $cantidad2.' ---22 ';
                                            list($entp2,$decp2) = explode('.', $cantidad2);

                                            $entp2 = $entp2  * $vectori['porc2'];

                                            list($entp,$decp) = explode('.', $entp2 + $entp1);
                                            $cantagregarpromo = $entp;
                                        }
                                    }
                                }

                                if(isset($cwpromocheckaux) and count($cwpromocheckaux) > 0 and $productopromo== 1) {
                                    ?>
                                    <tr>
                                        <td align="center" >&nbsp;</td>
                                        <td width="7%" height="30" align="center" >&nbsp;</td>
                                        <td width="5%" align="center">&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td align="center"class="tdlinebottom  " ><a href="cambiarProdInicio.php?cantidadagregar=<? echo $cantagregarpromo?>" class="ventanalocal"> - Seleccionar  producto Bonificado -</a></td>
                                        <td height="30" align="center" class="tdlinebottom  ">Bonificado</td>
                                        <td align="center"class="tdlinebottom  "> 0,00 </td>
                                        <td align="center" class="tdlinebottom  "><? echo $cantagregarpromo;?></td>
                                        <td align="center" class="tdlinebottom  ">&nbsp;</td>
                                        <td align="center" class="tdlinebottom  ">&nbsp;</td>
                                        <td align="center" class="tdlinebottom  ">&nbsp;</td>
                                        <td align="center" class="tdlinebottom  ">&nbsp;</td>
                                        <td align="center" class="tdlinebottom  ">&nbsp;</td>
                                        <td align="center" >&nbsp;</td>
                                    </tr>
                                <? }


                                if($noavanzafac)  {   ?>
                                    <script>  $('.botonirafacturar').hide()  </script>
                                <?  }?>
                            </table>



                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#saldomenu').html('BS = <? echo number_format($preciotttline, $decimalesPrecio, ',', '.')?> | USD = <? echo number_format($preciot, $decimalesPrecio, ',', '.')?>   ');
                                $('.preciotvalue').html('USD $<? echo number_format($preciot, $decimalesPrecio, ',', '.')?>');
                                $('.cantagregadavalue').html('<? echo $cantagregada ?>');
                            });
                        </script>
                    </div>
                </form>
            </div>
            <div class="col-md-2 botonesright" >

                <?
                if(strpos($_SESSION['opciones'],",41")>0  and !$noavanzafac ){
                    $resempresas = slqempresasid($linkms,$link,$idsession,$_SESSION['company_row']['id']);
                    while($listempres = mssql_fetch_array($resempresas)){


                        $linkurl = "verlistaproductos";

                        if($_SESSION['company_row']['soloinvnota'] == 1)
                            $linkurl = "verlistaproductosnota";

                        if($linkfacturacion)
                            $linkurl = $linkfacturacion;

                        ?>
                        <div class="cajapequenacolor color22 botonirafacturar" align="center" style="height:100px; width:93%; padding:2px; margin:2px">
                            <a href="<? echo $linkurl?>.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                               class="textocajitadestacado"
                               style="width:100%; margin-top:-10px; text-align:left;font-size:16px;"
                               accesskey="f"
                               title="Atajo: Alt+F">
                                <br>
                                <? echo ($facturacionPedidos)? ' CREAR PRESUPUESTO ' : 'FACTURAR'?> <br>
                                <span style="font-size:12px"><? echo $listempres['nombre']?> </span>
                                <span style="font-size:10px; display:block;">⌨️ Alt+F</span>
                            </a>
                        </div>

                        <? // echo " if($facturatxtmanual == 1 and ($sesionfacturatxt or $sesionfacturahoja)) {";
                        if($facturatxtmanual == 1 and ($sesionfacturatxt or $sesionfacturahoja)) {

                            ?>
                            <div class="cajapequenacolor color22 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px">
                                <a href="tramitarFactTxt.php?myDB=<? echo $companydb?>&clear=1"
                                   class="textocajitadestacado  " style="width:100%; font-size:16px;  text-align:left;">
                                    Factura fiscal
                                </a>
                            </div>

                        <? }

                    }


                }
                if($paneldeprocesos == 1){
                    if($menuclientes != '' ){
                        ?>
                        <div class="cajapequenacolor color14 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px">
                            <a href="generarProceso.php"
                               class="textocajitadestacado ventanalocal" style="width:100%; font-size:16px;  text-align:left;">
                                GENERAR PROCESO/ORDEN
                            </a>
                        </div>
                        <?

                    }else{
                        ?>
                        <div class="cajapequenacolor  " align="center" style="  height:46px; width:93%; padding:2px; margin:2px; background:#eee">
                            <a href="#"
                               class="textocajitadestacado  " style="width:100%; font-size:16px;  text-align:left; color:#CCCCCC">
                                GENERAR PROCESO/ORDEN
                            </a>
                        </div>
                    <? }
                }
                if($_SESSION["tipovieneguar"] == 'G'){
                    if(isset($_SESSION["codclie"]) and $_SESSION["codclie"] != '' and $_SESSION["vend"] and $_SESSION["vendedor"]
                        and isset($_SESSION["vieneguardada"]) and $_SESSION["vieneguardada"] != ''){


                        $pasaactu = 1;


                        if($sistemaoptico == 1){

                            $cantidades =0;
                            for($ii=1; $ii < $_SESSION['i']; $ii++){
                                if( $_SESSION['codserv'][$ii] != 1)
                                    $cantidades += $_SESSION['cantidad'][$ii];
                            }
                            if($cantidades >2) $pasaactu  =0;
                        }

                        if($pasaactu == 1  ){
                            ?>

                            <div class="cajapequenacolor color19 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px">
                                <a href="verlistaproductos.php?myDB=<? echo $companydb?>&actualizardatos=1&volverinicio=1"
                                   class="textocajitadestacado ventanalocal" id="btnactualizar" style="width:100%; font-size:16px;  text-align:left;">
                                    Actualizar Nro <?  echo (strlen($_SESSION["vieneguardada"])<8)? completarcadena( $link, $_SESSION["vieneguardada"],8,'0') :  $_SESSION["vieneguardada"]; ?>
                                </a>
                            </div>

                        <?   }else{


                            ?>

                            <div class="cajapequenacolor color19 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px;  background:#eee">
                                <a href="#"
                                   class="textocajitadestacado  " style="width:100%; font-size:16px;  text-align:left; color:#CCCCCC">
                                    Actualizar Nro <?  echo (strlen($_SESSION["vieneguardada"])<8)? completarcadena( $link, $_SESSION["vieneguardada"],8,'0') :  $_SESSION["vieneguardada"]; ?>
                                </a>
                            </div>

                        <?  }
                    }
                }
                if($idsession ==2223){ //$menuclientes
                    ?>
                    <div class="cajapequenacolor color24 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px">
                        <a href="facturasEspera.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                           class="textocajitadestacado ventanalocal" style="width:100%; font-size:16px;  text-align:left;">
                            FACTURAS EN ESPERA
                        </a>
                    </div>
                    <?
                }
                if(strpos($_SESSION['opciones'],",411")>0){
                    $resempresas = slqempresasid($linkms,$link,$idsession,$_SESSION['company_row']['id']);
                    while($listempres = mssql_fetch_array($resempresas)){
                        ?>
                        <div class="cajapequenacolor color22 " align="center" style="  height:46px; width:93%; padding:2px; margin:2px">
                            <a href="verlistaDevCompra.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                               class="textocajitadestacado ventanainterna" style="width:100%; font-size:16px;  text-align:left;">
                                DEV COMPRA <br> <span style="font-size:12px"> </span>
                            </a>
                        </div>

                    <? }
                }
                if(strpos($_SESSION['opciones'],",45")>0){


                        if ( $nosugerido != 1) {
                            ?>

                            <div class="cajapequenacolor color22 " align="center"
                                 style=" height:46px; width:93%; padding:2px; margin:2px">
                                <a href="nombreComboProductos.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>&monto=<?  echo $preciot; ?>"class="textocajitadestacado ventanainterna"
                                   style="width:100%; margin-top:-19px; text-align:left; font-size:16px;"><br />
                                    CREAR SUG VENTA
                                    <br>
                                    <span style="font-size:12px"> </span>
                                </a>
                            </div>


                            <?
                        }
                        if(strpos($_SESSION['opciones'],",588")>0){
                            ?>

                            <div class="cajapequenacolor color6 " align="center"
                                 style=" height:46px; width:93%; padding:2px; margin:2px">
                                <a href="hacerInventario.php?desplegar=1" class="textocajitadestacado" style="width:100%; text-align:left;font-size:16px;">
                                    CONTEO DE<br> INVENTARIO
                                </a>
                            </div>

                        <? }

                        if($_SESSION['company_row']['soloinvnota'] == 0){
                            ?>
                            <div class="cajapequenacolor color14 " align="center"
                                 style=" height:46px; width:93%; padding:2px; margin:2px">
                                <a href="listaOpei.php?myDB=<? echo $companydb?>&sinmenu=<? echo  $sinmenu ?>" class="   textocajitadestacado  "
                                   style="width:100%; margin-top:-19px; text-align:left;"><br />
                                    TRASLADO  <br>
                                    <span style="font-size:12px"> </span>
                                </a>
                            </div>
                        <? }
                        if(!$_SESSION['panicbutton'] and !$nadaNota){

                            $urltraslado = "listanewOpei.php";

                            ?>
                            <div class="cajapequenacolor color14 " align="center"
                                 style=" height:46px; width:93%; padding:2px; margin:2px">
                                <a href="<? echo $urltraslado?>?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>" class="textocajitadestacado  "
                                   style="width:100%; margin-top:-19px; text-align:left;"><br />
                                    --TRASLADO <br> <span style="font-size:12px"> </span>
                                </a>
                            </div>
                        <? }


                        if(strpos($_SESSION['opciones'],",204")>0){
                            if($_SESSION['company_row']['soloinvnota'] == 0){
                                ?>
                                <div class="cajapequenacolor color14 " align="center"
                                     style=" height:46px; width:93%; padding:2px; margin:2px">
                                    <a href="listaCargaFiscal.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                                       class="textocajitadestacado <? echo($sinmenu != 1)?' ':''?> operacioncargodescargo" style="width:100%;  margin-top:-19px;font-size:16px; text-align:left;"><br />
                                        CARGO/DESCARGO
                                        <br> <span style="font-size:12px"> </span>
                                    </a>
                                </div>
                            <? }
                            if(!$_SESSION['panicbutton'] and !$nadaNota){
                                ?>
                                <div class="cajapequenacolor color14 " align="center"
                                     style=" height:46px; width:93%; padding:2px; margin:2px">
                                    <a href="listaCargaDescarga.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                                       class="textocajitadestacado  " style="width:100%; margin-top:-19px; font-size:16px; text-align:left;"><br />
                                        --CARGO/DESCARGO
                                        <br> <span style="font-size:12px"> </span>
                                    </a>
                                </div>
                                <?
                            }
                        }
                        if(strpos($_SESSION['opciones'],",206")>0){
                            if($_SESSION['company_row']['soloinvnota'] == 0){
                                ?>
                                <div class="cajapequenacolor color14 " align="center"
                                     style=" height:46px; width:93%; padding:2px; margin:2px">
                                    <a href="listaCargaFiscal.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                                       class="textocajitadestacado  " style="width:100%;  margin-top:-19px;font-size:16px; text-align:left;"><br />
                                        CARGO
                                        <br> <span style="font-size:12px"> </span>
                                    </a>
                                </div>
                            <? }
                            if(!$_SESSION['panicbutton'] and !$nadaNota){
                                ?>
                                <div class="cajapequenacolor color14 " align="center"
                                     style=" height:46px; width:93%; padding:2px; margin:2px">
                                    <a href="listaCargaDescarga.php?myDB=<? echo $companydb?>&sinmenu=<? echo $sinmenu ?>"
                                       class="textocajitadestacado  " style="width:100%; margin-top:-19px; font-size:16px; text-align:left;"><br />
                                        --CARGO
                                        <br> <span style="font-size:12px"> </span>
                                    </a>
                                </div>
                                <?
                            }
                        }?>

                    <?
                }
                ?>
                <div class="cajapequenacolor color6" align="center" style="height:60px; width:93%; padding:2px; margin:2px">
                    <a href="inicioUsuario.php?eliminartodoslosproductos=1&sinmenu=<? echo $sinmenu ?>"
                       class="textocajitadestacado"
                       style="width:100%; text-align:left;font-size:16px;"
                       accesskey="l"
                       title="Limpiar lista (Alt+L)">
                        🧹 LIMPIAR LISTA ACTUAL
                        <span style="font-size:10px; display:block;">⌨️ Alt+L</span>
                    </a>
                </div>
                <div class="cajapequenacolor color22 " align="center"
                     style=" height:46px; width:93%; padding:2px; margin:2px">
                    <a href="ventasListaProd.php?sinmenu=<? echo $sinmenu ?>" style="width:100%; text-align:center; font-size:16px;">VENTAS DE PDTOS SELECCIONAODS </a>
                </div>
            </div>
        </div>

        <?
    }else{



        if(!$_SESSION['bienvenidabox'] or  $_SESSION['bienvenidabox'] == '1'){
            ?>
            <div class="row bienvenidabox gx-3">
                <div class="col-md-12 iniciobox col-sm-12 col-12">
                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 5px; overflow: hidden; color: #444;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-sm-6" style="text-align: left;">
                                    <div style="text-align: left; display: flex; justify-content: space-between">
                                        <div>
                                            <h3 class="mb-2 fw-bold text-white" style="margin-top: 0px !important;">¡Hola <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?>! 🎉</h3>
                                            <p class="text-white opacity-90 mb-3">Bienvenido al Sistema de Facturación y Gestión de Inventario</p>
                                        </div>
                                        <div>
                                            <i class="fa fa-info-circle me-1" style="font-size: xxx-large; color: #0288d1;"></i>
                                        </div>
                                    </div>
                                    <hr class="my-3 opacity-15" style="border-color: white;">
                                    <div class="mt-5">
                                        <p class="text-white mb-2 mt-5 ocultartasks" style="font-size: 1rem;">
                                        <h4 class="ocultartasks">¡Estamos mejorando para ti!</h4>
                                        </p>
                                        <p class="text-white-50 mb-2 ocultartasks" style="font-size: 1rem; line-height: 1.4;">
                                            Hemos simplificado la interfaz y reorganizado los módulos para que encuentres
                                            lo que necesitas de manera más rápida e intuitiva.
                                        </p>
                                        <ul class="text-white-50 mb-0 ocultartasks" style="font-size: 1rem; margin-top: 30px; padding-left: 1.2rem;">
                                            <li class="mb-1">✨ <strong>Búsqueda más rápida</strong> - Usa <kbd class="" style="background: #03b3b2; color: white;">Ctrl+F</kbd> y navega con flechas</li>
                                            <li class="mb-1">🎯 <strong>Interfaz simplificada</strong> - Menos clics, más productividad</li>
                                            <li class="mb-1">📱 <strong>Diseño responsive</strong> - Trabaja desde cualquier dispositivo</li>
                                            <li class="mb-1">🚀 <strong>Mejoras continuas</strong> - Estamos en constante evolución</li>
                                        </ul>

                                        <?
                                        if(isset($linkms) and isset($link) and isset($companydb) and isset($idsession)) {

                                            // Incluir el módulo con ruta correcta
                                            require_once(dirname(__FILE__) . '/modules/tareas/modulo_tareas.php');
                                            $moduloTareas = new ModuloTareas($linkms, $link, $companydb, $idsession);

                                            ?>
                                            <div onclick="$('.ocultartasks').fadeOut()" class="row mt-4" style=" margin-top: 40px !important; width: 100%; margin: auto;">
                                                <div class="col-12">
                                                    <?php include(dirname(__FILE__) . '/modules/tareas/modulo_tareas_ui.php'); ?>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            echo '<div class="alert alert-warning">No se pudo cargar el módulo de tareas</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <?php if (file_exists('iniciousuario.jpg')): ?>
                                        <img src="iniciousuario.jpg" class="img-fluid rounded-circle shadow-lg" style="max-width: 100%;" alt="Bienvenida">
                                    <?php else: ?>
                                        <div class="bg-white bg-opacity-25 rounded-circle p-4 d-inline-block">
                                            <i class="fa fa-store fa-4x text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="alert alert-warning" style="margin-top: 40px;">
                                        <small>Próximamente más novedades. ¡Gracias por ser parte de este crecimiento!</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AGREGAR AQUÍ EL MÓDULO DE TAREAS -->
            <?php
            if($_SESSION['bienvenidabox'] != '1')
                $_SESSION['bienvenidabox'] = 'chao';
        }

    }
    ?>

</div>