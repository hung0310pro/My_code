<?php
require_once "tit.php";
$conn = new titAuth($servername, $username, $password, $dbname);
if (isset($_GET['baocao'])):
    $this->layout('Student/layout2');

    $nhanvien = select_list($conn, 'select * from tktfc7p');
    $trangthai = select_list($conn, "select * from tteo37c where Tteo37c_QUa3y4 = 609");



    ?>


    <!--  <script>
          $(function () {
              $('#bang1').DataTable({
                  dom: 'Bfrtip',
                  buttons: ['copy', 'csv', 'pdf', 'print', {
                      extend: 'excel',
                      text: 'Export To Excel'
                  }]
              });
          });
      </script>-->
    <table id="bang1" class="table table-bordered table-striped table-hover text-center">
        <thead>
        <tr>
            <th>Nhân viên</th>
        <?php foreach ($trangthai as $value1){ ?>
            <th><?= $value1["Tteo37c_DNueVW"]; ?></th>
        <?php } ?>
            <th>Tổng</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($nhanvien as $value2){
            $idnv = select_info($conn, "select Id from user where Name = '".$value2["Tktfc7p_eLS2yK"]."' ")['Id'];
            ?>
            <tr>
                <td><?= $value2["Tktfc7p_p3VMRN"]; ?></td>
                <?php
                $tong = 0;
                foreach ($trangthai as $value1){
                    $diem = select_info($conn, "select count(*) as sl from t85rlsp where T85rlsp_i0p9Oe = ".$value1["Tteo37c_id"]. " and T85rlsp_owner = ".$idnv ); // chú ý
                    $tongdiem = $diem["sl"] * $value1["Tteo37c_POzBXu"];
                    $tong += $tongdiem;
                    ?>
                <td><?= number_format($tongdiem); ?></td>
                 <?php } ?>

                <td><?= number_format($tong); ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    <script>
        $(function () {
            $('#bang1').DataTable();
        });
    </script>
    <?php
    exit;
endif;

?>

<script type="text/javascript" src="datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="datatables/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="datatables/buttons.bootstrap.min.js"></script>
<script type="text/javascript" src="datatables/jszip.min.js"></script>
<script type="text/javascript" src="datatables/pdfmake.min.js"></script>
<script type="text/javascript" src="datatables/vfs_fonts.js"></script>
<script type="text/javascript" src="datatables/buttons.print.min.js"></script>
<script type="text/javascript" src="datatables/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="datatables/buttons.bootstrap.min.css">
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Báo cáo điểm tư vấn</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>  <!-- /.box-tools -->
    </div> <!-- /.box-header -->
    <div class="box-body">
        <div class="col-lg-12">
            <div class="col-lg-3 form-group input-group-sm">
                <label for="">Từ ngày</label>
                <input type="date" id="from" class="form-control" value="<?= firstofmonth($datetoday) ?>">
            </div>
            <div class="col-lg-3 form-group input-group-sm">
                <label for="">Đến ngày</label>
                <input type="date" id="to" class="form-control" value="<?= lastofmonth($datetoday) ?>">
            </div>
            <div class="col-lg-3">
                <br>
                <button class="btn btn-success" onclick="baocao()">Xác nhận</button>
            </div>
        </div>

        <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
        <script src="plugins/datatables/jquery.dataTables.js"></script>
        <script src="plugins/datatables/dataTables.bootstrap.js"></script>


    </div> <!-- /.box-body -->
    <div class="box-footer">
        <div id="show"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->


<script>
    function baocao() {
        var from = $('#from').val();
        var to = $('#to').val();
        $.ajax({
            url: "/tql13ye?baocao",
            dataType: "text",
            data: {from: from, to: to},
            type: "POST",
            success: function (data) {
                $('#show').html(data);
            },
            error: function () {
            }
        });

    }
</script>
