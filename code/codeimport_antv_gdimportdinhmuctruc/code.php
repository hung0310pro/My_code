<?php
require_once "tit.php";
$conn = new titAuth($servername, $username, $password, $dbname);

if (isset($_POST['submit'])) {
    $this->layout('Student/layout2');

    $sheet = $_POST['sheet'];
    $dulieu = json_decode($_POST['json'], true);
    $checkimport = $_POST['checkimport'];


    if ($checkimport == 'importde') {
        if (empty($_POST['Threyft_iY0JDq'])) {
            echo 1;
            exit;
        }
    } elseif ($checkimport == 'importmoi') {
        if (empty($_POST['T7dg3yw_kQUMWP'])) {
            echo 1;
            exit;
        }
    }

    $arr = [];
    foreach ($dulieu as $key_sheet => $value) {
        if ($key_sheet == $sheet) {
            foreach ($value as $key => $item) {
                $item = array_filter($item);
                if (!empty($item)) {
                    $arr[] = [
                        'stt' => $item[0],
                        'chucdanh' => $item[1],
                        'dinhmuc' => $item[2],
                        'catruc' => $item[3],
                    ];
                }
            }
        }
    }

    if ($checkimport == 'importde') {
        $datadel = [
            "Threyft_iY0JDq" => $_POST['Threyft_iY0JDq']
        ];
        deletedb($conn, "threyft", $datadel);

        foreach ($arr as $key => $item) {

            if ($key > 0) {
                $checkchucdanh = 0;
                if (empty($item['chucdanh'])) {
                    $checkchucdanh++;
                } else {
                    $cd = select_info($conn, $sql = "select count(*) from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['count(*)'];
                    if ($cd == 0) {
                        $checkchucdanh++;
                    }
                }

                if ($checkchucdanh == 0) {
                    $datains = [
                        "Threyft_iY0JDq" => $_POST['Threyft_iY0JDq'],
                        "Threyft_wdlXVq" => select_info($conn, $sql = "select * from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['Tbrac45_id'],
                        "Threyft_dDZ3IN" => select_info($conn, "select * from t6x8dvs where T6x8dvs_jO2bci = '" . trim($item["catruc"]) . "'")['T6x8dvs_id'],
                        "Threyft_Kl4FSs" => rmcomma($item['dinhmuc']),
                        "owner" => $User,
                    ];
                    insertdb($conn, "threyft", $datains);
                }
            }
        }
    } elseif ($checkimport == 'importmoi') {
        $datains = makedatanl($conn, "t7dg3yw", $_POST);
        $datains['owner'] = $User;
        insertdb($conn, "t7dg3yw", $datains);
        $iddm = lastinsertid($conn);

        foreach ($arr as $key => $item) {
            if ($key > 0) {
                $checkchucdanh = 0;
                if (empty($item['chucdanh'])) {
                    $checkchucdanh++;
                } else {
                    $cd = select_info($conn, $sql = "select count(*) from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['count(*)'];
                    if ($cd == 0) {
                        $checkchucdanh++;
                    }
                }

                if ($checkchucdanh == 0) {
                    $datains = [
                        "Threyft_iY0JDq" => $iddm,
                        "Threyft_wdlXVq" => select_info($conn, $sql = "select * from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['Tbrac45_id'],
                        "Threyft_dDZ3IN" => $item['$arr'],
                        "Threyft_Kl4FSs" => rmcomma($item['dinhmuc']),
                        "owner" => $User,
                    ];
                    insertdb($conn, "threyft", $datains);
                }
            }
        }
    }

    echo 2;
    exit;
}

if (isset($_GET['vebang'])) {
    $this->layout('Student/layout2');

    $sheet = $_POST['sheet'];
    $dulieu = json_decode($_POST['json'], true);

    $arr = [];
    foreach ($dulieu as $key_sheet => $value) {
        if ($key_sheet == $sheet) {  // key_sheet loại b, loại c...
            foreach ($value as $key => $item) {
                $item = array_filter($item);
                if (!empty($item)) {
                    $arr[] = [
                        'stt' => $item[0],
                        'chucdanh' => $item[1],
                        'dinhmuc' => $item[2],
                        'catruc' => $item[3],
                    ];
                }
            }
        }
    }

    ?>
    <style>
        .warning_check {
            background-color: #fffdb5 !important;
            border-color: #faebcc !important;
        }

        .alert-success {
            color: #3c763d !important;
            background-color: #dff0d8 !important;
            border-color: #d6e9c6 !important;
        }
    </style>

    <?php
    $checkchucdanh = 0;
    foreach ($arr as $key => $item) {
        if ($key > 0) {
            if (empty($item['chucdanh'])) {
                $checkchucdanh++;
            } else {
                $cd = select_info($conn, $sql = "select count(*) from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['count(*)'];
                if ($cd == 0) {
                    $checkchucdanh++;
                }
            }
        }
    }


    if ($checkchucdanh == 0) {
        ?>
        <!-- Div Success -->
        <div class="col-sm-12" id="divsuccess">
            <div class="alert alert-success alert-dismissible fade in">
                <strong style="text-decoration: underline">Success!</strong>&nbsp; &nbsp;
                <button type="button" class="btn btn-success btn-sm" onclick="clickHoanthanh()">Import</button>
            </div>
        </div>
        <!-- END Div Success -->
        <?php
    } else {
        ?>
        <!-- Div hiện lỗi -->
        <div class="col-sm-12" id="divhienloi">
            <div class="alert alert-success alert-dismissible fade in">
                <strong style="text-decoration: underline">Warning!</strong>&nbsp; Có <span id="masotrung"
                                                                                            style="font-weight: bold;text-decoration: underline"><?= $checkchucdanh ?></span>
                <?= $arr[0]['chucdanh'] ?> bị trùng hoặc trống.
                <br><br>

                <span>Bạn có muốn tiếp tục import?</span> &nbsp;
                <button type="button" class="btn btn-success btn-sm" onclick="clickHoanthanh()">Có</button>
                &nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-danger btn-sm" onclick="clickxoaloi()">Không</button>
            </div>
        </div>
        <!-- END Div hiện lỗi -->
        <?php
    }
    ?>

    $arr[0]['stt'] cái này là cái tiêu đề của file excel ví dụ là Số thứ tự
    <div class="col-sm-12">
        <table class="table table-bordered table-striped table-hover text-center">
            <thead>
            <tr>
                <th width="6%" style="text-align: center"><?= $arr[0]['stt'] ?></th>
                <th style="text-align: center"><?= $arr[0]['chucdanh'] ?></th>
                <th style="text-align: center"><?= $arr[0]['dinhmuc'] ?></th>
                <th style="text-align: center"><?= $arr[0]['catruc'] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($arr as $key => $item) {
                if ($key > 0) {
                    $checkchucdanh = 0;

                    if (empty($item['chucdanh'])) {
                        $checkchucdanh++;
                    } else {
                        $cd = select_info($conn, $sql = "select count(*) from tbrac45 where Tbrac45_HcQBgm = '" . trim($item['chucdanh']) . "'")['count(*)'];
                        if ($cd == 0) {
                            $checkchucdanh++;
                        }
                    }

                    ?>
                    <tr>
                        <td style="text-align: center"><?= $item['stt'] ?></td>
                        <td style="text-align: center"
                            class="<?= $checkchucdanh > 0 ? "warning_check" : "" ?>"><?= $item['chucdanh'] ?></td>
                        <td style="text-align: center"><?= $item['dinhmuc'] ?></td>
                        <td style="text-align: center"><?= $item['catruc'] ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
    exit;

}

if (isset($_GET['docfile'])) {
    $this->layout('Student/layout2');

    $dulieu = json_decode($_POST['json'], true);
    $arr = []; // lấy sheet1, sheet2..

    foreach ($dulieu as $key => $value) {
        $arr[$key] = $key;
    }

    echo json_encode($arr);

    exit;
}

?>

<link rel="stylesheet" href="/plugins/iCheck/all.css">
<link rel="stylesheet" href="/smile/officialib/css/smile.css">
<link rel="stylesheet" href="/smile/officialib/css/font-awesome.min.css">
<style>
    .chosen-container {
        position: relative;
        display: inline-block;
        vertical-align: middle;
        font-size: 13px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        width: 100% !important;
    }
</style>

<div class="panel">
    <div class="box-header with-border" style="border-bottom: none;">
        <h4 class="box-title"><i class="fa fa-list"></i> Giao diện import định mức trực</h4>
    </div>
    <div class="panel-body" style="border-top: 1px solid #f4f4f4;">
        <!-- chọn dạng Json -->
        <select name="format" onchange="setfmt()" style="display: none">
            <option value="json" selected> JSON</option>
        </select>
        <!-- END chọn -->

        <div class="row">
            <div class="col-sm-3" style="height:85px">
                <div class="form-group">
                    <label for="T2fl9rv_ClvGYy">Chọn file</label>
                    <input type="file" name="xlfile" id="xlf">
                </div>
            </div>
            <div class="col-sm-3" style="height:85px;text-align: center;padding-top: 18px;">
                <button type="button" class="btn btn-info" onclick="docfile()">Hiển thị dữ liệu</button>
            </div>

            <div class="col-sm-3" style="height: 85px;"></div>

            <div class="col-sm-3 pull-right" style="height:85px;text-align: right;padding-top: 18px;">
                <a href="/antv/dinhmuc.xlsx">
                    <button type="button" class="btn btn-success">Tải file import mẫu</button>
                </a>
            </div>

            <div class="col-sm-3" style="height:85px;display: none" id="hiensheet">
                <div class="form-group input-group-sm">
                    <label>Chọn Sheet</label>
                    <select name="chonsheet" id="chonsheet" class="form-control chosen-select" onchange="vebang()">
                        <option value="0">---</option>
                    </select>
                </div>
            </div>
        </div>


        <!-- hiện thị Json -->
        <input type="hidden" id="out">
        <!-- END hiện thị -->

        <!--<div class="col-sm-12" id="test"></div>-->
        <!-- div cây -->
        <div class="row">
            <div class="col-sm-12">
                <div id="filetrong" style="display: none">
                    <code>Chưa chọn file Excel</code>
                </div>
            </div>
        </div>

        <form id="frmsubmit" style="margin-bottom: 0">
            <div class="row">
                <div class="col-sm-12" id="hiencheck" style="display: none">
                    <div class="form-horizontal" id="datraodoichitiet">
                        <div class="col-sm-2">
                            <div class=" radio-primary">
                                <label style="font-weight: normal"
                                       onclick="clickimportde()">
                                    <input type="radio"
                                           name="checkimport"
                                           class="custom-radio flat-red" style="font-weight: normal"
                                           value="importde" checked
                                           onclick="clickimportde()">
                                    import đè
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class=" radio-primary">
                                <label style="font-weight: normal"
                                       onclick="clickimportmoi()">
                                    <input type="radio"
                                           name="checkimport"
                                           class="custom-radio flat-red" style="font-weight: normal"
                                           value="importmoi"
                                           onclick="clickimportmoi()">
                                    import mới
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <hr>
            </div>


            <div class="row" id="hienimportde" style="display: none">
                <?php
                selectbox5($conn, "Threyft_iY0JDq", '', 3);
                ?>
            </div>

            <div class="row" id="hienimportmoi" style="display: none">
                <?php
                $option = [];
                drawform($conn, "t7dg3yw", $option, 3, $notshow);
                ?>
            </div>
        </form>

        <hr style="margin-top: 0">
        <!--<div class="col-sm-12">
            <div id="test"></div>
        </div>-->
        <div class="row">
            <div id="hienbang"></div>
        </div>
        <!-- END div cây -->

    </div> <!-- /.box-body -->
</div>


<script src="/plugins/xlsx/cpexcel.js"></script>
<script src="/plugins/xlsx/shim.js"></script>
<script src="/plugins/xlsx/jszip.js"></script>
<script src="/plugins/xlsx/xlsx.js"></script>
<script src="/js/makealert.js" type="text/javascript"></script>

<!-- script -->
<script type="text/javascript">

    $(function () {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        })
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red'
        })
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })

        $("[class*='icheckbox'],[class*='iradio']").each(function () {
            $(this).find("ins").attr("onclick", $(this).find("input").attr("onclick"));
        });

    });

    function clickimportde() {
        $('#Threyft_iY0JDq').val('').trigger('chosen:updated');
        $('#hienimportde').css('display', 'block');
        $('#hienimportmoi').css('display', 'none');
    }

    function clickimportmoi() {
        $('#Threyft_iY0JDq').val('').trigger('chosen:updated');
        $('#hienimportde').css('display', 'none');
        $('#hienimportmoi').css('display', 'block');
    }

    function clickxoaloi() {
        $("#divhienloi").hide();
        $("#divsuccess").hide();
    }

    function docfile() {
        $("#filetrong").css('display', 'none');
        $('#chonsheet').empty().trigger("chosen:updated");
        if ($('#xlf').val() == '' || $('#xlf').val() == null) {
            $("#filetrong").css('display', 'block');
        } else {
            var json = $('#out').val();
            var re_json1 = json.replace(/\\r\\n/g, '');
            var re_json2 = re_json1.replace(/\\t/g, '');

            $.ajax({
                url: "/t5ehxvr?docfile",
                //async: false,
                dataType: "json",
                data: {json: re_json2},
                type: "POST",
                success: function (data) {
                    $('#chonsheet').empty();
                    $('#chonsheet').append($("<option></option>")
                        .attr("value", 0)
                        .text('---'));
                    $.each(data, function (key, value) {
                        $('#chonsheet').append($("<option></option>")
                            .attr("value", value)
                            .text(value));
                    });
                    $('#chonsheet').trigger("chosen:updated");

                    $('#hiensheet').css('display', 'block');
                },
                error: function () {
                }
            });
        }
    }

    function vebang() {
        $("#filetrong").css('display', 'none');
        $("#hiencheck").css('display', 'none');
        $("#hienimportde").css('display', 'none');
        $("#hienimportmoi").css('display', 'none');
        $('#hienbang').empty();
        if ($('#xlf').val() == '' || $('#xlf').val() == null) {
            $("#filetrong").css('display', 'block');
        } else {
            var json = $('#out').val();
            var re_json1 = json.replace(/\\r\\n/g, '');
            var re_json2 = re_json1.replace(/\\t/g, '');
            var sheet = $('#chonsheet').val();
            // loại b, or loại c
            $('#hienbang').empty();
            if (sheet != 0) { // giá trị mặc định ban đầu khi chưa chọn
                $.ajax({
                    url: "/t5ehxvr?vebang",
                    //async: false,
                    dataType: "text",
                    data: {json: re_json2, sheet: sheet},
                    type: "POST",
                    success: function (data) {
                        $('#hienbang').html(data);
                        $("#hiencheck").css('display', 'block');
                        clickimportde();
                        $('html, body').animate({scrollTop: $('#hiensheet').offset().top}, 1000);
                    },
                    error: function () {
                    }
                });
            }
        }
    }

    function clickHoanthanh() {
        var json = $('#out').val();
        var re_json1 = json.replace(/\\r\\n/g, '');
        var re_json2 = re_json1.replace(/\\t/g, '');
        var sheet = $('#chonsheet').val();
        var formData = new FormData($('#frmsubmit')[0]);
        formData.append('json', re_json2);
        formData.append('sheet', sheet);
        formData.append('submit', '');
        $.ajax({
            type: 'post',
            url: '/t5ehxvr',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (data) {
                if (data == 1) {
                    makeAlertright("Vui lòng điền đầy đủ thông tin.", 3000);
                } else {
                    aftersubmit('', 'btn');
                    makeSAlertright("Thành công", 3000);
                    window.setTimeout(function () {
                        location.reload()
                    }, 700);
                }
            }
        }); //End Ajax
    }

</script>

<style>
    .modal123 {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, .8) url('/img/loadding.gif') 50% 50% no-repeat;
    }

    /* When the body has the loading class, we turn
	   the scrollbar off with overflow:hidden */
    body.loading .modal123 {
        overflow: hidden;
    }

    /* Anytime the body has the loading class, our
	   modal element will be visible */
    body.loading .modal123 {
        display: block;
    }
</style>
<script>
    $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });
</script>
<div class="modal123"><!-- Place at bottom of page --></div>

<!-- Json file excel -->
<script type="text/javascript">
    /*jshint browser:true */
    /* eslint-env browser */
    /*global Uint8Array, console */
    /*global XLSX */
    /* exported b64it, setfmt */
    /* eslint no-use-before-define:0 */
    var X = XLSX;
    var XW = {
        /* worker message */
        msg: 'xlsx',
        /* worker scripts */
        worker: '/plugins/xlsx/xlsxworker.js'
    };

    var global_wb;

    var process_wb = (function () {
        var OUT = document.getElementById('out');

        var get_format = (function () {
            var radios = document.getElementsByName("format");
            return function () {
                for (var i = 0; i < radios.length; ++i) if (radios[i].checked || radios.length === 1) return radios[i].value;
            };
        })();

        var to_json = function to_json(workbook) {
            var result = {};
            workbook.SheetNames.forEach(function (sheetName) {
                var roa = X.utils.sheet_to_json(workbook.Sheets[sheetName], {header: 1});
                if (roa.length) result[sheetName] = roa;
            });
            return JSON.stringify(result, 2, 2);
        };

        return function process_wb(wb) {
            global_wb = wb;
            var output = "";
            switch (get_format()) {
                case "json":
                    output = to_json(wb);
                    break;
                default:
                    output = to_json(wb);
            }
            if (OUT.value === undefined) OUT.textContent = output;
            else OUT.value = output;
            if (typeof console !== 'undefined') console.log("output", new Date());
        };
    })();

    var setfmt = window.setfmt = function setfmt() {
        if (global_wb) process_wb(global_wb);
    };

    var do_file = (function () {
        var rABS = typeof FileReader !== "undefined" && (FileReader.prototype || {}).readAsBinaryString;
        var domrabs = false;
        if (!rABS) domrabs.disabled = !(domrabs.checked = false);

        var use_worker = typeof Worker !== 'undefined';
        var domwork = false;
        if (!use_worker) domwork.disabled = !(domwork.checked = false);

        var xw = function xw(data, cb) {
            var worker = new Worker(XW.worker);
            worker.onmessage = function (e) {
                switch (e.data.t) {
                    case 'ready':
                        break;
                    case 'e':
                        console.error(e.data.d);
                        break;
                    case XW.msg:
                        cb(JSON.parse(e.data.d));
                        break;
                }
            };
            worker.postMessage({d: data, b: rABS ? 'binary' : 'array'});
        };

        return function do_file(files) {
            rABS = domrabs.checked;
            use_worker = domwork.checked;
            var f = files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                if (typeof console !== 'undefined') console.log("onload", new Date(), rABS, use_worker);
                var data = e.target.result;
                if (!rABS) data = new Uint8Array(data);
                if (use_worker) xw(data, process_wb);
                else process_wb(X.read(data, {type: rABS ? 'binary' : 'array'}));
            };
            if (rABS) reader.readAsBinaryString(f);
            else reader.readAsArrayBuffer(f);
        };
    })();


    (function () {
        var xlf = document.getElementById('xlf');
        if (!xlf.addEventListener) return;

        function handleFile(e) {
            do_file(e.target.files);
        }

        xlf.addEventListener('change', handleFile, false);

    })();
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36810333-1']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- --END-- -->
