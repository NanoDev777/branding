<?php

namespace App\Http\Controllers;

use App\Amount;
use App\Cost;
use App\Price;
use App\Product;
use App\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportesController extends Controller
{
    protected $quotation;

    public function __construct(Quotation $quotations)
    {
        $this->quotation = $quotations;
    }

    public function getPrices($quantity)
    {
        $prices = Price::orderBy('id', 'ASC')->get();
        $val    = [];
        $c      = 0;
        foreach ($prices as $key => $value) {
            if ($c === 0) {
                $val = $value;
            }
            if ($quantity <= $val->quantity) {
                return $val;
            }
            if ($quantity < $value->quantity) {
                if ($quantity >= $val->quantity) {
                    return $val;
                }
            }
            $val = $value;
            $c++;
        }
    }

    public function getAmount($product, $quantity)
    {
        $amounts = Amount::where('product_id', $product)->orderBy('id', 'ASC')->get();
        $val     = [];
        $c       = 0;
        $min     = $amounts[0]->quantity;
        foreach ($amounts as $value) {
            if ($value->quantity < $min) {
                $min = $value->quantity;
            }
        }

        foreach ($amounts as $key => $value) {
            if ($c === 0) {
                $val = $value;
            }
            if ($quantity <= $val->quantity) {
                return [
                    'min'     => $min,
                    'amounts' => $val,
                ];
            }
            if ($quantity < $value->quantity) {
                if ($quantity >= $val->quantity) {
                    return [
                        'min'     => $min,
                        'amounts' => $val,
                    ];
                }
            }
            $val = $value;
            $c++;
        }
    }

    public function savePDF(Request $request)
    {
        $detailsData  = json_decode($request->details, true);
        $productsData = json_decode($request->data, true);
        PDF::SetTitle('Cotización');
        PDF::setHeaderCallback(function ($pdf) {
            $image_file = public_path('img/branding.png');
            $pdf->Image($image_file, 5, 5, 77, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->setCellPaddings(1, 1, 1, 1);
            $pdf->setCellMargins(1, 1, 1, 1);
            $pdf->SetFillColor(250, 250, 242);
            $txt = "La Paz: Pedro Blanco Esq. Nicolas Acosta Nº 1471\nTeléf - Fax:  (591) 2004313 - 78978762\nSanta Cruz: Av 2 de Agosto calle 6 lado capilla\nTeléf - Cel:  (591) 3494677 - 76722731";
            $pdf->MultiCell(115, 5, $txt, 1, 'L', 1, 0, '', '', true);
            $pdf->SetFont('times', '', 10);
            $pdf->MultiCell(90, 1, 'www.brandingbolivia.com', 1, 'C', 1, 0, 82, 29, true);
        });

        PDF::setFooterCallback(function ($pdf) use ($detailsData) {
            $fecha = date('Y');
            $html  = '<div style="background-color:#fffd79;color:black; text-align: center;"><span style="font-size: xx-small;">Copyright © ' . $fecha . ' Branding Todos los derechos reservados.</span></div><hr>';
            $pdf->writeHTMLCell($w = 190, $h = 0, $x = '10', $y = '290', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $style = array(
                'border'  => false,
                'padding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
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
            $footer .= "<tr><td colspan='3'><strong>Plazo de Entrega: </strong>{$detailsData['term']} Días Hábiles a Partir de la Aprobación del Cliente, Incluye logotipo 1 color.</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Forma de Pago: </strong>Contra entrega de factura</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Validez Cotización: </strong>40 Días</td></tr>";
            $footer .= "<tr><td colspan='3'><strong>Garantía: </strong>Todos nuestros productos cuentan con certificación de calidad Europea de 1 año</td></tr>";
            $footer .= "</table>";
            PDF::writeHTMLCell($w = 187, $h = 0, $x = '11', $y = '255', $footer, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        });

        $array      = array();
        $productsId = array();
        $errors     = array(); //array de errores
        $extra      = 0;
        foreach ($productsData as $key => $value) {
            $prices  = $this->getPrices($value['quantity']);
            $amounts = $this->getAmount($value['id'], $value['quantity']);
            //aqui verifivar cantidades y agregar al array de errores
            if ($value['quantity'] < $amounts['min']) {
                $errors[] = ['code' => $value['code'], 'name' => $value['name'], 'min' => $amounts['min']];
            }

            $value['extra'] > 0 ? $extra = $value['extra'] : $extra = 0;

            $cost = Cost::all()->first();

            $cbn = round($amounts['amounts']->price / $cost['chilean'], 2);

            $transfer  = round($cbn * $cost['transfer'], 2);
            $import    = round($cbn * $cost['amount'], 2);
            $transport = round($cbn * $cost['transport'], 2);

            $product = round($cbn + $transfer + $import + $transport + $prices['logo'], 2);
            $utility = round($product * $prices['utility'], 2);
            $sf      = round($product + $utility, 2);
            $iva     = round($sf * $cost['iva'], 2);
            $total   = round($sf + $iva, 2);

            $name = Product::join('images', 'images.product_id', '=', 'products.id')
                ->where('products.id', $value['id'])
                ->where('images.id', $value['url'])
                ->select('products.code', 'products.name', 'products.description', 'products.width', 'products.height', 'products.thickness', 'products.weight', 'images.image', DB::raw("{$value['quantity']} as quantity"), DB::raw("{$total} as unitary"), DB::raw("{$extra} as extra"))
                ->first();
            $array[$key]              = $name;
            $productsId[$value['id']] = ['quantity' => $value['quantity'], 'total' => $total];
        }

        //aqui preguntar si mi array de errores tiene valores y retornarlo
        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors'  => $errors,
            ]);
        }

        $count     = Quotation::count() + 1;
        $quotation = [
            'cite'     => $count,
            'customer' => $detailsData['customer'],
            'contact'  => $detailsData['contact'],
            'phone'    => $detailsData['phone'],
            'address'  => $detailsData['address'],
            'term'     => $detailsData['term'],
            'products' => $productsId,
        ];
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
            $details .= "<tr><td colspan='3'><strong>Empresa / Cliente: </strong>" . $detailsData['customer'] . "</td></tr>";
            $details .= "<tr><td colspan='3'><strong>Persona Contacto: </strong>" . $detailsData['contact'] . "</td></tr>";
            $details .= "<tr><td colspan='3'><strong>Teléfono: </strong>" . $detailsData['phone'] . "</td></tr>";
            $details .= "<tr><td colspan='3'><strong>Dirección: </strong>" . $detailsData['address'] . "</td></tr>";
            $details .= "</table>";

            $dataNumRows = count($array);
            $div         = $dataNumRows / 2;
            $page        = round($div);
            $a           = 0;
            $b           = 1;
            for ($i = 0; $i < $page; $i++) {
                PDF::AddPage();

                $cite = "<b>Cite: " . $count . "/" . date('y') . "</b>";
                PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '30', $cite, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $date = "<b>" . date('d-m-Y') . "</b>";
                PDF::writeHTMLCell($w = 189, $h = 0, $x = '175', $y = '30', $date, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '37', $details, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                PDF::SetFillColor(255, 253, 153);
                PDF::MultiCell(187, 7, 'COTIZACIÓN', 1, 'C', 1, 2, 11, 69, true);

                $url = public_path('img/products/' . $array[$a]->image);
                PDF::setJPEGQuality(75);
                PDF::Image($url, 11, 77, 88, 80, '', '', '', true, 100, '', false, false, 1, false, false, false);

                $extra1  = round($array[$a]->quantity * $array[$a]->extra, 2);
                $product = "";
                $product .= '<style type=text/css>';
                $product .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 4px;}';
                $product .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF}';
                $product .= 'td{font-size: x-small;';
                $product .= '</style>';
                $product .= "<table>";
                $product .= "<tr><th><b>" . $array[$a]->name . "</b></th></tr>";
                $product .= "<tr><td><b>Código :</b> " . $array[$a]->code . "</td></tr>";
                $product .= "<tr><td><b>Descripción :</b></td></tr>";
                $product .= "<tr><td>" . $array[$a]->description . "</td></tr>";
                $product .= "<tr><td><b>Dimensiones: </b> " . $array[$a]->width . " cm / " . $array[$a]->height . " cm / " . $array[$a]->thickness . " cm | " . $array[$a]->weight . " gr</td></tr>";
                $product .= "<tr><td><b>Cantidad: </b> " . $array[$a]->quantity . "&nbsp; &nbsp;<b>Logo Extra :</b> " . $array[$a]->extra . " Bs.</td></tr>";
                $product .= "<tr><td><b>Precio Unitario: </b>" . number_format($array[$a]->unitary, 2, '.', ',') . " Bs. &nbsp; &nbsp; <b>Total: </b>" . number_format($array[$a]->quantity * $array[$a]->unitary + $extra1, 2, '.', ',') . " Bs.</td></tr>";
                $product .= "</table>";
                PDF::writeHTMLCell($w = 99, $h = 0, $x = '100', $y = '77', $product, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

                if ($b < $dataNumRows) {
                    $url2 = public_path('img/products/' . $array[$b]->image);
                    PDF::setJPEGQuality(75);
                    PDF::Image($url2, 11, 162, 88, 80, '', '', '', true, 100, '', false, false, 1, false, false, false);
                    $extra2   = round($array[$b]->quantity * $array[$b]->extra, 2);
                    $product2 = "";
                    $product2 .= '<style type=text/css>';
                    $product2 .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 4px;}';
                    $product2 .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF}';
                    $product2 .= 'td{font-size: x-small;';
                    $product2 .= '</style>';
                    $product2 .= "<table>";
                    $product2 .= "<tr><th><b>" . $array[$b]->name . "</b></th></tr>";
                    $product2 .= "<tr><td><b>Código :</b> " . $array[$b]->code . "</td></tr>";
                    $product2 .= "<tr><td><b>Descripción :</b></td></tr>";
                    $product2 .= "<tr><td>" . $array[$b]->description . "</td></tr>";
                    $product2 .= "<tr><td><b>Dimensiones: </b> " . $array[$b]->width . " cm / " . $array[$b]->height . " cm / " . $array[$b]->thickness . " cm | " . $array[$b]->weight . " gr</td></tr>";
                    $product2 .= "<tr><td><b>Cantidad: </b> " . $array[$b]->quantity . "&nbsp; &nbsp;<b>Logo Extra :</b> " . $array[$b]->extra . " Bs.</td></tr>";
                    $product2 .= "<tr><td><b>Precio Unitario: </b>" . number_format($array[$b]->unitary, 2, '.', ',') . " Bs. &nbsp; &nbsp; <b>Total: </b>" . number_format($array[$b]->quantity * $array[$b]->unitary + $extra2, 2, '.', ',') . " Bs.</td></tr>";
                    $product2 .= "</table>";
                    PDF::writeHTMLCell($w = 99, $h = 0, $x = '100', $y = '162', $product2, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                }
                $a += 2;
                $b += 2;
            }
            $file = 'Cotizacion.pdf';
            PDF::Output(public_path($file), 'F');
            return response()->json([
                'success' => true,
                'data'    => $file,
            ]);
        } else {
            return response()->json(['message' => message('MSG010')], 500);
        }
    }

    public function detailPDF($id)
    {
        PDF::SetTitle('Detalle');
        PDF::setHeaderCallback(function ($pdf) {
            $image_file = public_path('img/branding.png');
            $pdf->Image($image_file, 5, 5, 77, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $pdf->setCellPaddings(1, 1, 1, 1);
            $pdf->setCellMargins(1, 1, 1, 1);
            $pdf->SetFillColor(250, 250, 242);
            $txt = "La Paz: Pedro Blanco Esq. Nicolas Acosta Nº 1471\nTeléf - Fax:  (591) 2004313 - 78978762\nSanta Cruz: Av 2 de Agosto calle 6 lado capilla\nTeléf - Cel:  (591) 3494677 - 76722731";
            $pdf->MultiCell(115, 5, $txt, 1, 'L', 1, 0, '', '', true);
            $pdf->SetFont('times', '', 10);
        });

        PDF::setFooterCallback(function ($pdf) {
            $fecha = date('Y');
            $html  = '<div style="background-color:#fffd79;color:black; text-align: center;"><span style="font-size: xx-small;">Copyright © ' . $fecha . ' Branding Todos los derechos reservados.</span></div><hr>';
            $pdf->writeHTMLCell($w = 190, $h = 0, $x = '10', $y = '290', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $style = array(
                'border'  => false,
                'padding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
            );
            PDF::write2DBarcode('www.tcpdf.org', 'QRCODE,H', 178, 260, 20, 20, $style, 'N');
        });
        PDF::AddPage();

        $quotation = Quotation::with('products')->findOrFail($id);

        $state = '';
        switch ($quotation['state']) {
            case 0:
                $state = 'PENDIENTE';
                break;
            case 1:
                $state = 'APROBADO';
                break;
            case 2:
                $state = 'ANULADO';
                break;
            default:
                $state = 'PENDIENTE';
        }

        $details = '';
        $details .= '<style type=text/css>';
        $details .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 5px;}';
        $details .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF; font-size: small}';
        $details .= "tr{background-color: #FAFAF2;}";
        $details .= "td{font-size: x-small;}";
        $details .= '</style>';
        $details .= "<table>";
        $details .= "<thead><tr><th colspan='3'><strong>DETALLES</strong></th></tr></thead>";
        $details .= "<tr><td colspan='3'><strong>Empresa / Cliente: </strong>" . $quotation['customer'] . "</td></tr>";
        $details .= "<tr><td colspan='3'><strong>Persona Contacto: </strong>" . $quotation['contact'] . "</td></tr>";
        $details .= "<tr><td colspan='3'><strong>Teléfono: </strong>" . $quotation['phone'] . "</td></tr>";
        $details .= "<tr><td colspan='3'><strong>Dirección: </strong>" . $quotation['address'] . "</td></tr>";
        $details .= "<tr><td colspan='3'><strong>Plazo de Entrega: </strong>" . $quotation['term'] . " Días</td></tr>";
        $details .= "<tr><td colspan='3'><strong>Estado: </strong>" . $state . "</td></tr>";
        $details .= "</table>";
        PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '40', $details, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        $cite = "<h2>Cite: " . $quotation['cite'] . "</h2>";
        PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '33', $cite, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $date = "<b> Fecha de Emisión: </b>" . $quotation['created_at']->format('d/m/Y');
        PDF::writeHTMLCell($w = 189, $h = 0, $x = '135', $y = '33', $date, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $cite = "<h3>Productos Requeridos</h3>";
        PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '85', $cite, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        $total = 0;
        $html  = "";
        $html .= '<style type=text/css>';
        $html .= 'table {border:1px black solid; font-family: dejavusans;  border-spacing: 3px;}';
        $html .= 'th{color: #fff; font-family: dejavusans; background-color: #AFAFAF; text-align: center; font-size:x-small;}';
        $html .= "tr{background-color: #F0F0F0;}";
        $html .= "td{font-size: x-small; text-align: center; }";
        $html .= '</style>';
        $html .= "<table>";
        $html .= "<thead><tr>";
        $html .= "<th style=\"width: 10%;\"><strong>Código</strong></th>";
        $html .= "<th style=\"width: 35%;\"><strong>Nombre</strong></th>";
        $html .= "<th style=\"width: 17%;\"><strong>Cantidad</strong></th>";
        $html .= "<th style=\"width: 20%;\"><strong>Precio Unitario</strong></th>";
        $html .= "<th style=\"width: 18%;\"><strong>Total</strong></th>";
        $html .= "</tr></thead>";
        foreach ($quotation['products'] as $value) {
            $sum = $value->pivot->quantity * $value->pivot->total;
            $html .= "<tr>";
            $html .= "<td style=\"width: 10%;\">" . $value->code . "</td>";
            $html .= "<td style=\"width: 35%;\">" . $value->name . "</td>";
            $html .= "<td style=\"width: 17%;\">" . $value->pivot->quantity . "</td>";
            $html .= "<td style=\"width: 20%;\">" . number_format($value->pivot->total, 2, '.', ',') . "</td>";
            $html .= "<td style=\"width: 18%;\">" . number_format($sum, 2, '.', ',') . "</td>";
            $html .= "</tr>";
            $total = $total + $sum;
        }
        $html .= "<tr>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td>-----------------------------</td>";
        $html .= "<td>--------------------------</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td></td>";
        $html .= "<td><b>TOTAL:</b></td>";
        $html .= "<td><b>" . number_format($total, 2, '.', ',') . " Bs</b></td>";
        $html .= "</tr>";
        $html .= "</table>";
        PDF::writeHTMLCell($w = 189, $h = 0, $x = '10', $y = '93', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

        $file = 'Detalle.pdf';
        PDF::Output(public_path($file), 'F');
        return response()->json(["data" => $file]);
    }

    public function deletePDF(Request $request)
    {
        $file = public_path($request->file);
        $do   = unlink($file);
        if ($do != true) {
            return response('true');
        } else {
            return response('false');
        }
    }

}
