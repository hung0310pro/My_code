<?php
require_once "tit.php";
$conn = new titAuth($servername, $username, $password, $dbname);

if (isset($_GET["submit"])) {
    $this->layout('Student/layout2');

    $data = $_POST["data"];
    foreach ($data as $value) {
        $value = array_filter($value);  // đây là lấy giữ liệu và insert và 1 mảng,cái
        // array_filter là loại bỏ những dòng mà nó trống k điền gì

        if (!empty($value)) {
            $datainsert = [
                'Tkhmip6_9DR1sA' => $value[0],
                'Tkhmip6_VYtD5c' => $value[1],
                'Tkhmip6_m4xb8P' => $value[2],
                'Tkhmip6_YFNHeT' => $value[3],
                'Tkhmip6_ZKaOPN' => $value[4],
                'Tkhmip6_WkeBTy' => $value[5]
            ];
            insertdb($conn, 'tkhmip6', $datainsert);
        }
    }

    exit;
}

?>
<script src="/js/makealert.js" type="text/javascript"></script>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>  <!-- /.box-tools -->
    </div> <!-- /.box-header -->
    <div class="box-body">


        <div id="example"></div>
        <script src="/handsontable/dist/handsontable.full.js"></script>
        <link rel="stylesheet" media="screen" href="/handsontable/dist/handsontable.full.css">
        <link rel="stylesheet" media="screen" href="/handsontable/plugins/bootstrap/handsontable.bootstrap.css">
        <script src="/js/makealert.js" type="text/javascript"></script>
        <script src="/handsontable/lib/handsontable-chosen-editor.js"></script>

        <script>
            var container = document.getElementById('example');
            var hot = new Handsontable(container, {
                minSpareRows: 1,
                rowHeaders: true,
                stretchH: "all",
                colHeaders: ["Diễn giải", "Khách hàng", "Mã chi tiết", "Đơn vị tính", "Đơn giá(khách hàng)", "Đơn giá(nhà thầu)"],
                contextMenu: true,
                columns: [
                    {type: 'text',},
                    {type: 'text',},
                    {type: 'text',},
                    {type: 'text',}, // nếu cột dữ liệu là chữ
                    {type: 'numeric', format: '0,0'},
                    {type: 'numeric', format: '0,0'}, // nếu cột dữ liệu là số


                ]
            });

        </script>
    </div> <!-- /.box-body -->
    <div class="box-footer">
        <button onclick="clickHT()" class="btn btn-primary">Submit</button>
    </div><!-- /.box-body -->
</div><!-- /.box -->


<script type="text/javascript">
    function clickHT() {
        var data = hot.getData();  // đây là đã lấy hết dữ liệ từ bảng hansome rồi
        $.ajax({
            url: "/tj2s9gl?submit",
            dataType: "text",
            data: {data: data},
            type: "POST",
            success: function (data) {
                aftersubmit('', 'btn');
                makeSAlertright("Thành công", 3000);
                window.setTimeout(function () {
                    location.reload()
                }, 500);
            },
            error: function () {
            }
        });
    }
</script>