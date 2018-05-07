<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Product;
use App\quotation;

class ReportesController extends Controller
{
  protected $quotation;

    public function __construct(Quotation $quotations){
      $this->quotation = $quotations;
    }

 
    public function savePDF(Request $request)
    {
        PDF::SetTitle('Cotización');
        PDF::setHeaderCallback(function($pdf) {
            $image_file = public_path('img/branding.png');
            $pdf->Image($image_file, 5, 5, 77, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->setCellPaddings(1, 1, 1, 1);
            $pdf->setCellMargins(1, 1, 1, 1);
            $pdf->SetFillColor(250, 250, 242);
            $txt = "La Paz: Pedro Blanco Esq. Nicolas Acosta Nº 1471\nTeléf - Fax:  (591) 2004313 - 78978762\nSanta Cruz: Av 2 de Agosto calle 6 lado capilla\nTeléf - Cel:  (591) 3494677 - 76722731";
            $pdf->MultiCell(115, 5,$txt, 1, 'L', 1, 0, '', '', true);
        }); 

        PDF::setFooterCallback(function($pdf) {
            $fecha = date('Y');
            $html = '<div style="background-color:#fffd79;color:black; text-align: center;"><span style="font-size: xx-small;">Copyright © '.$fecha.' Branding Todos los derechos reservados.</span></div><hr>';
            $pdf->writeHTMLCell($w = 190, $h = 0, $x = '10', $y = '290', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $style = array(
              'border' => false,
              'padding' => 'auto',
              'fgcolor' => array(0,0,0),
              'bgcolor' => false
            );
            PDF::write2DBarcode('www.tcpdf.org', 'QRCODE,H', 178, 260, 20, 20, $style, 'N');

            $footer = '';
            $footer .= '<style type=text/css>';
            $footer .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 5px;}';
            $footer .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF; font-size: small}';
            $footer .= "tr{background-color: #FAFAF2;}";
            $footer .= "td{font-size: x-small;}";            
            $footer .= '</style>';
            $footer .= "<table>";
            $footer .= "<tr><td colspan='3'><strong>Plazo de Entrega: </strong>20 Días Hábiles a Partir de la Aprobación del Cliente</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Forma de Pago: </strong>Contra entrega de factura</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Validez Cotización: </strong>40 Días</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Garantía: </strong>Todos nuestros productos cuentan con certificación de calidad Europea de 1 año</td></tr>";
            $footer .= "</table>";
            PDF::writeHTMLCell($w = 187, $h = 0, $x = '11', $y = '255', $footer, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        }); 
         
        $detailsData = json_decode($request->details, true);
        $productsData = json_decode($request->data, true);

        $array = array();
        $productsId = array();
        foreach ($productsData as $key => $value){
          $name = Product::join('images','images.product_id','=','products.id')
                  ->where('products.id', $value['id'])
                  ->where('images.id', $value['url'])
                  ->select('products.code', 'products.name', 'products.description', 'products.width', 'products.height', 'products.thickness', 'products.weight', 'images.image')
                  ->first();
          $array[$key] = $name;
          $productsId[$value['id']] = ['quantity' => $value['quantity']];
        }

        $count = Quotation::count() + 1;
        $quotation = ['cite' => $count, 'customer' => $detailsData['customer'], 'total' => 10000, 'products' => $productsId];
        $query = $this->quotation->saveQuotation($quotation);   
        if ($query) {
          $details = '';
          $details .= '<style type=text/css>';
          $details .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 5px;}';
          $details .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF; font-size: small}';
          $details .= "tr{background-color: #FAFAF2;}";
          $details .= "td{font-size: x-small;}";            
          $details .= '</style>';
          $details .= "<table>";
          $details .= "<thead><tr><th colspan='3'><strong>DETALLES</strong></th></tr></thead>";
          $details .= "<tr><td colspan='3'><strong>Empresa / Cliente: </strong>".$detailsData['customer']."</td></tr>";
          $details .= "<tr><td colspan='3'><strong>Persona Contacto: </strong>".$detailsData['contact']."</td></tr>";
          $details .= "<tr><td colspan='3'><strong>Teléfono: </strong>".$detailsData['phone']."</td></tr>";
          $details .= "<tr><td colspan='3'><strong>Dirección: </strong>".$detailsData['adress']."</td></tr>";
          $details .= "</table>";
      
          $dataNumRows = count($array);
          $div =$dataNumRows/2;
          $page = round($div);
          $a = 0;
          $b = 1;
          for ($i = 0; $i < $page; $i++){
            PDF::AddPage();

            $cite = "<b>Cite: ".$count."/".date('y')."</b>";
            PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '30', $cite, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $date = "<b>".date('d-m-Y')."</b>";
            PDF::writeHTMLCell($w = 189, $h = 0, $x = '175', $y = '30', $date, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '35', $details, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            PDF::SetFillColor(255, 253, 153);
            PDF::MultiCell(187, 7, 'COTIZACIÓN', 1, 'C', 1, 2, 11, 69, true);

            $url = public_path($array[$a]->image);
            PDF::setJPEGQuality(75);
            PDF::Image($url, 11, 77, 88, 80, '', '', '', true, 100, '', false, false, 1, false, false, false);
            
            $product = "";
            $product .= '<style type=text/css>';
            $product .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 4px;}';
            $product .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF}';
            $product .= 'td{font-size: x-small;';            
            $product .= '</style>';
            $product .= "<table>";
            $product .= "<tr><th><b>".$array[$a]->name."</b></th></tr>";
            $product .= "<tr><td><b>Código :</b> ".$array[$a]->code."</td></tr>";
            $product .= "<tr><td><b>Descripción :</b></td></tr>";
            $product .= "<tr><td>".$array[$a]->description."</td></tr>";
            $product .= "<tr><td><b>Dimensiones: </b> ".$array[$a]->width." cm / ".$array[$a]->height." cm / ".$array[$a]->thickness." cm | ".$array[$a]->weight." gr</td></tr>";
            $product .= "</table>";
            PDF::writeHTMLCell($w = 99, $h = 0, $x = '100', $y = '77', $product, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true); 

            if ($b < $dataNumRows) {
              $url2 = public_path($array[$b]->image);
              PDF::setJPEGQuality(75);
              PDF::Image($url2, 11, 170, 88, 80, '', '', '', true, 100, '', false, false, 1, false, false, false);            
              $product2 = "";
              $product2 .= '<style type=text/css>';
              $product2 .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 4px;}';
              $product2 .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF}';
              $product2 .= 'td{font-size: x-small;';            
              $product2 .= '</style>';
              $product2 .= "<table>";
              $product2 .= "<tr><th><b>".$array[$b]->name."</b></th></tr>";
              $product2 .= "<tr><td><b>Código :</b> ".$array[$b]->code."</td></tr>";
              $product2 .= "<tr><td><b>Descripción :</b></td></tr>";
              $product2 .= "<tr><td>".$array[$b]->description."</td></tr>";
              $product2 .= "<tr><td><b>Dimensiones: </b> ".$array[$b]->width." cm / ".$array[$b]->height." cm / ".$array[$b]->thickness." cm | ".$array[$b]->weight." gr</td></tr>";
              $product2 .= "</table>";
              PDF::writeHTMLCell($w = 99, $h = 0, $x = '100', $y = '170', $product2, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);   
            }
            $a+=2;
            $b+=2;   
          }
          $file = 'Cotizacion.pdf';
          PDF::Output(public_path($file), 'F');
          return response()->json(["data" => $file]);
        }
        else {
          return response()->json(["error" => 'error'], 500);
        }  
    }
 
    public function deletePDF(Request $request)
    {    
        $file = public_path($request->file);
        $do = unlink($file);
        if($do != true){
          return response('true');
        }else{
          return response('false');
        }
    }
 
}
