<style>
    /* ... (โค้ดส่วน font-face และ @page คงเดิม) ... */
    
    @font-face {
        font-family: 'LineSeed';
        font-style: normal;
        font-weight: normal;
        src: url('<?php echo plugins_url("Quote-Generator/assets/font/LINESeedSansTH_Rg.ttf"); ?>');
    }

    * {
        font-family: 'LineSeed', Arial, sans-serif;
        box-sizing: border-box;
    }

    @page {
        margin-top: 160px;
        margin-bottom: 50px; 
        header: html_myHeader; 
    }

    htmlpageheader {
        display: none;
    }

    .header-container {
        width: 100%;
        border-bottom: 1.5px solid #808080;
        padding-bottom: 25px;
        background-color: #fff;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 0;
    }

    /* --- จุดที่แก้ไข 1: ปรับลดความกว้างฝั่งซ้ายลง --- */
    .brand-section {
        width: 55%; /* ลดจาก 70% เหลือ 55% เพื่อแบ่งที่ให้ฝั่งขวา */
        vertical-align: bottom;
        text-align: left;
    }

    .small-logo {
        width: 80px;
        height: auto;
        display: block;
        margin-bottom: 10px;
    }

    .company-name {
        margin: 0;
        font-size: 12px;
        white-space: nowrap;
        color: #000;
    }

    /* --- จุดที่แก้ไข 2: ขยายความกว้างฝั่งขวา --- */
    .quotation {
        width: 45%; /* เพิ่มจาก 30% เป็น 45% */
        vertical-align: bottom;
        text-align: right;
    }

    .quotation h1 {
        margin: 0 0 5px 0;
        font-size: 24px;
        font-weight: bold;
        line-height: 1;
    }

    .quotation p {
        margin: 0;
        font-size: 11px;
        line-height: 1.4;
    }

    /* --- จุดที่แก้ไข 3: บังคับให้อยู่บรรทัดเดียวกัน --- */
    #QuotationText p {
        white-space: nowrap; /* ห้ามตัดบรรทัดเด็ดขาด */
        margin-top: 5px;
    }

    body {
        font-size: 14px;
    }
</style>
<htmlpageheader name="myHeader" style="display:none">
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="brand-section">
                    <img src="https://quotation.tbs-marketing.com/wp-content/uploads/2024/03/TBS-Logo.png" 
                         alt="TBS Logo" 
                         class="small-logo">
                    <p class="company-name"><?php echo $company_name; ?></p>
                </td>

                <td class="quotation">
                    <h1>Quotation</h1>
                    <p>
                        <strong>Quote Date : </strong> <?php echo date('d/m/Y', strtotime($quotation_date)); ?><br>
                        <strong>Valid Until : </strong> <?php echo date('d/m/Y', strtotime($quotation_valid_date)); ?>
                    </p>

                    <div id="QuotationText">
                        <p>
                            <strong>Quotation number : </strong>
                            <?php 
                            // ตรวจสอบว่ามีข้อมูลตัวแปรหรือไม่ ก่อนแสดงผล
                            if (isset($running_number)) {
                                echo isset($revision_number) && $revision_number 
                                     ? $running_number . '_R' . $revision_number 
                                     : $running_number; 
                            } else {
                                echo "-"; // กรณีไม่มีเลขที่ ให้แสดงขีดหรือค่าว่าง
                            }
                            ?>
                        </p>
                    </div>
                    </td>
            </tr>
        </table>
    </div>
</htmlpageheader>