<?php
$_POST = wp_unslash($_POST);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_package = isset($_POST['package_name']) ? $_POST['package_name'] : 'No package selected';
}
// --- Fallbacks ---
$column_to_print = $column_to_print ?? (isset($_POST['column_to_print']) ? (array) $_POST['column_to_print'] : null);
$currency_symbol = $currency_symbol ?? (
    isset($_POST['currency'])
    ? (($_POST['currency'] === 'USD') ? '$' : (($_POST['currency'] === 'GBP') ? '£' : (($_POST['currency'] === 'EUR') ? '€' : (($_POST['currency'] === 'SGD') ? 'S$' : '฿'))))
    : '฿'
);
$included_prices = $included_prices ?? [];
$discount = isset($discount) ? $discount : (isset($_POST['discount']) ? floatval($_POST['discount']) : 0);
$discount_type = $discount_type ?? (isset($_POST['discount_type']) ? $_POST['discount_type'] : 'fixed');
$vat_percentage = isset($vat_percentage) ? $vat_percentage : (isset($_POST['vat_percentage']) ? floatval($_POST['vat_percentage']) : 0);
$total_amount = isset($total_amount) ? floatval($total_amount) : (isset($_POST['total_amount_without_vat_and_discount']) ? floatval($_POST['total_amount_without_vat_and_discount']) : 0);
$discount_sub_total = $discount_sub_total ?? 0;
$vat_amount = isset($vat_amount) ? floatval($vat_amount) : (isset($_POST['total_vat_amount']) ? floatval($_POST['total_vat_amount']) : 0);
$total_amount_with_vat = isset($total_amount_with_vat) ? floatval($total_amount_with_vat) : (isset($_POST['total_amount_with_vat']) ? floatval($_POST['total_amount_with_vat']) : ($total_amount - $discount_sub_total + $vat_amount));
$monthly_price = $monthly_price ?? (isset($_POST['monthly_price_input']) ? $_POST['monthly_price_input'] : '');
$full_name = $full_name ?? (isset($_POST['full_name']) ? $_POST['full_name'] : '');
$company_phone = $company_phone ?? (isset($_POST['company_phone']) ? $_POST['company_phone'] : '');
$company_email = $company_email ?? (isset($_POST['company_email']) ? $_POST['company_email'] : '');
?>

<style>
    @font-face {
        font-family: 'Prompt';
        src: url('<?php echo plugins_url("Quote-Generator/assets/font/Prompt-Regular.ttf"); ?>') format('truetype');
        font-style: normal;
        font-weight: normal;
    }

    /* ===== Global ===== */
    * {
        font-family: 'Prompt', 'lineseed', Arial, sans-serif;
        line-height: 1.2;
    }

    body {
        margin-top: 180px; /* เว้นที่ให้ Header */
        font-size: 10pt;
    }
    @page {
        margin-bottom: 50px; 
        
        /* สั่งให้ใช้ Header เดิม (ถ้ามี) */
        header: html_myHeader; 
        
        /* --- เพิ่มบรรทัดนี้เพื่อเรียกใช้เลขหน้า --- */
        footer: html_myPageNumberFooter; 
    }

    /* ===== Table Logic for Page Breaking (สำคัญมาก) ===== */
    .service-table-container table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border: 1px solid #000;
        
        /* อนุญาตให้ตัดตารางข้ามหน้าได้ */
        page-break-inside: auto; 
    }
    

    .service-table-container tr {
        /* ห้ามตัดข้อความภายในแถวเดียวกันขาดครึ่ง (ให้อยู่ด้วยกันทั้งแถว) */
        page-break-inside: avoid; 
        page-break-after: auto;
    }

    .service-table-container thead {
        display: table-header-group; /* ให้หัวตารางซ้ำทุกหน้า */
    }

    .service-table-container tfoot {
        display: table-row-group;
    }

    .service-table-container thead th {
        background-color: #3C3D3A;
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        text-align: left;
        padding: 10px;
        overflow: hidden;
        white-space: nowrap;
    }

    /* Column Widths */
    .service-table-container thead th:nth-child(1) { width: 50px; }  /* # */
    .service-table-container thead th:nth-child(2) { width: auto; }  /* Description */
    .service-table-container thead th:nth-child(3) { width: 60px; }  /* Qty */
    .service-table-container thead th:nth-child(4) { width: 100px; } /* Rate */
    .service-table-container thead th:nth-child(5) { width: 120px; } /* Amount */

.service-table-container td {
        /* แก้ไขบรรทัดนี้: */
        padding: 8px 5px; 
        /* 8px = เพิ่มความสูงบรรทัดให้พอดีๆ (ไม่เบียดเกินไป) */
        /* 5px = ลดระยะซ้ายขวาให้ตารางดูแน่นขึ้น (ตามโจทย์เดิม) */
        
        font-size: 12px;
        word-break: break-word;
        vertical-align: top; /* แนะนำให้ใช้ top เพื่อให้บรรทัดเริ่มเท่ากัน */
    }

    
    .remarks-box {
        width: 96%;
        background: lightgrey; 
        padding: 15px 20px; 
        word-break: break-word; 
        
        /* ถ้าพื้นที่เหลือน้อย ให้ดันไปหน้าใหม่ทั้งก้อน */
        page-break-inside: avoid; 
    }

    /* Header Info Table */
    .info-header-table { width: 100%; border-collapse: collapse; margin-top: -10px; }
    .info-header-table td { vertical-align: top; width: 50%; padding: 5px; }
    .info-header-table table td { padding: 2px 0; font-size: 16px; border: none; }

    /* Footer */
    .footer { width: 100%; text-align: center; }
    .content-middle { margin-bottom: 40px; text-align: center; }
    


    .fake-rowspan-top {
        border-top: 1px solid #000;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-bottom: none !important;
    }

    .fake-rowspan-middle {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: none !important;
        border-bottom: none !important;
    }

    .fake-rowspan-bottom {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-bottom: 1px solid #000 !important; /* บังคับตีเส้นล่าง */
        border-top: none !important; 
    }
    
    .fake-rowspan-single {
        border: 1px solid #000 !important;
    }

    /* บังคับให้ตารางตัดหน้าได้ */
    .service-table-container table {
        page-break-inside: auto;
    }
    
    .service-table-container tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
</style>

<div id="quotation">
    <table class="info-header-table" >
        <tr>
            <td>
                <h4 style="margin: 0 0 10px 0; text-decoration: underline; font-size: 18px;">Customer</h4>
                <table>
                    <tr><td style="font-size:14px;"><?php echo esc_html( wp_unslash($client_name) ); ?></td></tr>
                    <?php if (!empty($customer_tax_id)): ?>
                        <tr><td style="font-size:14px;"> <?php echo htmlspecialchars($customer_tax_id); ?></td></tr>
                    <?php endif; ?>
                    <tr><td style="font-size:14px;"><?php echo nl2br(htmlspecialchars($client_address)); ?></td></tr>
                    <tr><td style="font-size:14px;"> <?php echo htmlspecialchars($client_email); ?></td></tr>
                    <tr><td style="font-size:14px;"> <?php echo htmlspecialchars($client_phone); ?></td></tr>
                </table>
            </td>
            <td>
                <h4 style="margin: 0 0 10px 0; text-decoration: underline; font-size: 18px;">Quote/Project Description</h4>
                <table>
                    <tr>
                        <td style="word-wrap: break-word; white-space: normal; font-size:14px;">
                            <?php echo nl2br( esc_html( wp_unslash($project_desc) ) ); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php
    // Prepare Data
    $items = [];
    if (!empty($_POST['item']) && is_array($_POST['item'])) {
        $count = count($_POST['item']);
        for ($i = 0; $i < $count; $i++) {
            $qty = (int)($_POST['quantity'][$i] ?? 1);
            $price = (float)($_POST['price'][$i] ?? 0);
            $items[] = [
                'row_id'      => $_POST['row_id'][$i] ?? ($i + 1),
                'parent_id'   => $_POST['parent_id'][$i] ?? null,
                'group_id'    => $_POST['group_id'][$i] ?? 'ungrouped',
                'description' => wp_unslash($_POST['item'][$i] ?? ''),
                'quantity'    => $qty,
                'price'       => $price,
                'total_price' => $qty * $price,
            ];
        }
    }

    $groups = [];
    foreach ($items as $item) {
        $gid = $item['group_id'] ?: 'ungrouped';
        $groups[$gid][] = $item;
    }

    $service_group_names = $_POST['service_group_name'] ?? [];
    $service_display_names = $_POST['service_display_name'] ?? [];
    $include_all_map = [];
    foreach (array_keys($groups) as $gid) {
        $include_all_map[$gid] = isset($_POST["include_all_{$gid}"]) && $_POST["include_all_{$gid}"] == '1';
    }

    $colspan = 3; 
    if (!is_null($column_to_print) && in_array('Qty', $column_to_print)) $colspan++;
    if (!is_null($column_to_print) && in_array('Rate', $column_to_print)) $colspan++;
    ?>

    <?php if (!empty($groups)): ?>
    <div class="service-table-container">
        <table autosize="1" style="width: 100%; border-collapse: collapse; overflow: wrap;">
            <thead>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th>Description</th>
                    <?php if (!is_null($column_to_print) && in_array('Qty', $column_to_print)) : ?>
                        <th style="text-align: center;">Qty.</th>
                    <?php endif; ?>
                    <?php if (!is_null($column_to_print) && in_array('Rate', $column_to_print)) : ?>
                        <th style="text-align: center;">Rate</th>
                    <?php endif; ?>
                    <th style="text-align: center;">Amount</th>
                </tr>
            </thead>
                    <tbody style="border: 1px solid #000;">
                    <?php foreach ($groups as $gid => $group_items): ?>
                        <?php
                        $group_include_all = $include_all_map[$gid] ?? false;
                        $group_name = trim($service_group_names[$gid] ?? '') ?: 'General Services';
                        $display_name = !empty($service_display_names[$gid]) ? trim($service_display_names[$gid]) : $group_name;
                        $display_name = ($display_name === '') ? '-' : $display_name;
                        
                        $group_total = 0;
                        foreach ($group_items as $it) $group_total += $it['total_price'];
                        $total_rows = count($group_items);

                        // คำนวณหาแถวตรงกลางเพื่อวางราคา
                        $middle_row_index = floor(($total_rows - 1) / 2); 
                        ?>

                        <tr style="border: 1px solid #000; page-break-inside: avoid;">
                            <td colspan="<?php echo $colspan; ?>" style="background:#eee; font-weight:bold; padding: 10px; border: 1px solid #000;">
                                Service: <?php echo htmlspecialchars($display_name); ?>
                            </td>
                        </tr>

                        <?php 
                        $parent_counter = 1;
                        foreach ($group_items as $index => $item):
                            $is_parent_row = strpos($item['row_id'], '.') === false;
                            $display_row_id = $is_parent_row ? $parent_counter++ : $item['row_id'];
                            
                            $is_last_row = ($index === $total_rows - 1);
                            $is_first_row = ($index === 0);
                            
                            // CSS พื้นฐานสำหรับเส้นขอบ
                            $border_top = "border-top: 1px solid #000;";
                            $border_bottom = "border-bottom: 1px solid #000;";
                            $border_left = "border-left: 1px solid #000;";
                            $border_right = "border-right: 1px solid #000;";
                            $no_border_top = "border-top: none;";
                            $no_border_bottom = "border-bottom: none;";
                            
                            // บังคับตีเส้นล่างที่ TR สำหรับแถวสุดท้าย (ใช้ box-shadow ช่วยกันเหนียว)
                            $tr_style = "";
                            if ($is_last_row) {
                                $tr_style = "border-bottom: 1px solid #000;"; 
                            }
                        ?>
                            <tr style="<?php echo $tr_style; ?>">
                                
                                <td style="width: 10%; text-align:center; <?php echo $border_top . $border_bottom . $border_left; ?> border-right: none;">
                                    <?php echo htmlspecialchars($display_row_id); ?>
                                </td>

                                <td style="padding-left:10px; width: 50%; <?php echo $border_top . $border_bottom; ?> border-left: none; border-right: none; <?php echo $is_parent_row ? 'font-weight: bold;' : 'font-weight: normal;'; ?>">
                                    <?php echo esc_html( wp_unslash($item['description']) ); ?>
                                </td>

                                <?php if (!is_null($column_to_print) && in_array('Qty', $column_to_print)) : ?>
                                    <td style="padding-left:10px; <?php echo $border_top . $border_bottom; ?> border-left: none; border-right: none; text-align: center;">
                                        <?php echo $item['quantity']; ?>
                                    </td>
                                <?php endif; ?>

                                <?php if (!is_null($column_to_print) && in_array('Rate', $column_to_print)) : ?>
                                    <td style="padding-left:10px; <?php echo $border_top . $border_bottom; ?> border-left: none; border-right: none; text-align: right;">
                                        <?php echo $item['price'] == 0 ? 'Included' : $currency_symbol . number_format($item['price'], 0); ?>
                                    </td>
                                <?php endif; ?>

                                <?php if ($group_include_all): ?>
                                    <?php 
                                        // เริ่มต้น Style พื้นฐาน (ซ้าย/ขวามีเส้นเสมอ)
                                            $amount_style = "text-align: center; padding: 8px 5px; " . $border_left . $border_right;                                        
                                        // จัดการเส้นขอบบน/ล่าง และ Box Shadow (ไม้ตายแก้เส้นหาย)
                                        if ($total_rows == 1) {
                                            // แถวเดียว: ตีครบทุกด้าน
                                            $amount_style .= $border_top . $border_bottom . " vertical-align: middle;";
                                        } elseif ($is_first_row) {
                                            // แถวแรก: ตีบน, ไม่ตีล่าง
                                            $amount_style .= $border_top . $no_border_bottom . " vertical-align: top;";
                                        } elseif ($is_last_row) {
                                            // แถวสุดท้าย: ไม่ตีบน, ตีล่าง (และเพิ่ม Box Shadow สีดำข้างล่าง 1px เพื่อความชัวร์)
                                            $amount_style .= $no_border_top . $border_bottom . " vertical-align: top; box-shadow: 0px 1px 0px 0px #000;";
                                        } else {
                                            // แถวกลาง: ไม่ตีบน/ล่าง
                                            $amount_style .= $no_border_top . $no_border_bottom . " vertical-align: top;";
                                        }

                                        // แสดงราคาตรงแถวกลาง
                                        if ($index == $middle_row_index) {
                                            $show_price = true;
                                            $amount_style .= " vertical-align: middle;"; 
                                        } else {
                                            $show_price = false;
                                        }
                                    ?>
                                    <td style="<?php echo $amount_style; ?>">
                                        <?php 
                                            if ($show_price) {
                                                echo $currency_symbol . " " . number_format($group_total, 0); 
                                            } else {
                                                echo '&nbsp;'; 
                                            }
                                        ?>
                                    </td>
                                <?php else: ?>
                                    <td style="vertical-align: middle; <?php echo $border_top . $border_bottom . $border_left . $border_right; ?> text-align: center; padding: 5px;">
                                        <?php echo $item['price'] == 0 ? '-' : $currency_symbol . number_format($item['total_price'], 0); ?>
                                    </td>
                                <?php endif; ?>

                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="summary-wrapper">
        
        <div class="summary-container" style="width: 100%; margin-top: 20px;">
            <table style="width: 45%; margin-left: auto; border-collapse: collapse; table-layout: fixed; margin-bottom: 20px;">
                <tr>
                    <td style="text-align: left; padding: 8px 10px;"><strong>Sub Total</strong></td>
                    <td style="text-align: right; padding: 8px 10px;"><?php echo $currency_symbol; ?> <?php echo number_format($total_amount, 2); ?></td>
                </tr>
                <?php if ($discount != 0) : ?>
                    <tr>
                        <td style="text-align: left; padding: 8px 10px;">
                            <strong>Discount</strong> <?php echo ($discount_type === 'percentage') ? '('.number_format($discount, 0).'%)' : ''; ?>
                        </td>
                        <td style="text-align: right; padding: 8px 10px;">- <?php echo $currency_symbol; ?> <?php echo number_format($discount_sub_total, 2); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($vat_percentage != 0) : ?>
                    <tr>
                        <td style="text-align: left; padding: 8px 10px;"><strong>VAT (<?php echo number_format($vat_percentage, 0); ?>%)</strong></td>
                        <td style="text-align: right; padding: 8px 10px; "><?php echo $currency_symbol; ?> <?php echo number_format($vat_amount, 2); ?></td>
                    </tr>
                <?php endif; ?>
                <tr style="background-color: #D3D3D3;">
                    <td style="text-align: left; padding: 12px 10px; font-weight: bold; border-top: 1px solid #fff;">Total Amount</td>
                    <td style="text-align: right; padding: 12px 10px; border-top: 1px solid #fff;">
                        <?php 
                            echo $currency_symbol . ' ' . number_format($total_amount_with_vat, 2); 
                            if (!empty($monthly_price)) echo ' / ' . $monthly_price;
                        ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php
        // Process remarks content
        $processed_remark = '';
        if (!empty($remark)) {
            $lines = preg_split("/\r\n|\n|\r/", $remark);
            foreach ($lines as $line) {
                $trim = trim($line);
                if ($trim === '') {
                    $processed_remark .= '<br>';
                    continue;
                }
                if (strpos($trim, 'Service:') === 0) {
                    $processed_remark .= '<p style="margin:0;"><strong>' . htmlspecialchars($trim) . '</strong></p>';
                } else {
                    $processed_remark .= '<p style="margin:0;">' . nl2br(htmlspecialchars($trim)) . '</p>';
                }
            }
        } else {
            $processed_remark = '<p>-</p>';
        }
        ?>

        <div class="remarks-box">
            <h4 style="margin: 0 0 10px 0; text-decoration: underline; font-size: 16px;"><u>Remarks</u></h4>
            <div class="remarks" style="margin-top: 0px;">
                <div style="margin-top: -10px; "><?php echo $processed_remark; ?></div>
            </div>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <div class="content-above" style="margin-top: 20px;">
            <p>Above information is not an invoice and only an estimate of services/goods described above.
                <br>Payment will be collected in prior to provision of services/goods described in this quote.
            </p>
        </div>

            <div class="content-middle" style="width: 100%; margin-top: 20px;">
        <div style="border-bottom: 1.5px solid #000; width: 100%; margin-bottom: 20px; height: 1px;"></div>
        <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
            <tr>
                <td style="width: 30%; padding-bottom: 5px;">Signature:</td>
                <td style="width: 5%;"></td> <td style="width: 30%; padding-bottom: 5px;">Print name:</td>
                <td style="width: 5%;"></td> <td style="width: 30%; padding-bottom: 5px;">Date Fields:</td>
            </tr>
            
            <tr>
                <td style="border: 1px solid #000; height: 30px;">&nbsp;</td>
                <td style="border: none;"></td>
                <td style="border: 1px solid #000; height: 30px;">&nbsp;</td>
                <td style="border: none;"></td>
                <td style="border: 1px solid #000; height: 30px;">&nbsp;</td>
            </tr>
        </table>

        <p style="margin-top: 15px; text-align: center;">
            Please confirm your acceptance of this quote by signing this document
        </p>

        <div style="border-bottom: 1.5px solid #000; width: 100%; margin-top: 20px; height: 1px;"></div>

    </div>

       <div class="content-below" style="text-align: center;">
        
        <h3 style="margin-top: 5px; margin-bottom: 5px;">Thank you for your business!</h3>
        
        <div style="line-height: 1.3; font-size: 12px;"> <p style="margin: 2px 0;">
                Should you have any enquiries concerning this quote, please contact <?php echo htmlspecialchars($full_name); ?> at <?php echo !empty($company_phone) ? htmlspecialchars($company_phone) : htmlspecialchars($company_email); ?>
            </p>
            
            <p style="margin: 2px 0;">
                1023, 4th Floor TPS Building Pattanakarn Road, Suanluang, Bangkok, Thailand, 10250
            </p>
            
            <p style="margin: 2px 0;">
                Tel: 02-007-5800 | Email: <?php echo htmlspecialchars($company_email); ?> | Website: www.tbs-marketing.com
            </p>
            
        </div>
    </div>

</div>
<htmlpagefooter name="myPageNumberFooter">
    <table width="100%" style="border-top: 1px solid #000; font-size: 10px;">
        <tr>
            <td width="50%"></td> <td width="50%" style="text-align: right;">
                Page {PAGENO} of {nbpg}
            </td>
        </tr>
    </table>
</htmlpagefooter>