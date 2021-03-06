<?php
$this->layout('Student/layout1');
if (isset($_POST['mang'])) {
	$infopt = select_info($conn, "SELECT * FROM t1rqwvk WHERE T1rqwvk_id = '" . $_GET['id'] . "'");
	$tongtienpb = 0;
	foreach ($_POST['mang'] as $val) {
		$tongtienpb += $val['phanbo'];
	}
	echo number_format($infopt['T1rqwvk_lcAKLV'] - $infopt['T1rqwvk_7l2iVU'] - $tongtienpb);
	exit;
}
if (isset($_POST['mang2'])) {
	$infopt = select_info($conn, "SELECT * FROM t1rqwvk WHERE T1rqwvk_id = '" . $_GET['id'] . "'");
	$tongtienpb = 0;
	foreach ($_POST['mang2'] as $val) {
		$tongtienpb += $val['phanbo'];
	}
	echo number_format($infopt['T1rqwvk_7l2iVU'] + $tongtienpb);
	exit;
}
if (isset($_GET['sbmxn'])) {
	$infopt = select_info($conn, "SELECT * FROM t1rqwvk WHERE T1rqwvk_id = '" . $_GET['id'] . "'");
	$tongtienpb = 0;
	$mangtien = json_decode($_POST['tongtienpb'], true);

	$arrgui = [];

	foreach ($mangtien as $val) {
		$tongtienpb += $val['phanbo'];
		$insert = array(
			"Tblz1re_oI5l8A" => $val['id'],
			"Tblz1re_yiZhvx" => date('Y-m-d'),
			"Tblz1re_SBDlEb" => $val['phanbo'],
			"Tblz1re_5mc4E3" => number2word($val['phanbo'])
		);
		insertdb($conn, 'tblz1re', $insert);
		$idpb = lastinsertid($conn);

		$arrgui[] = $idpb;

		$tienhopdong = select_info($conn, "SELECT * FROM tvmaon5 WHERE Tvmaon5_id = '" . $val['id'] . "'");
		$tiendadong = $tienhopdong['Tvmaon5_r3vwM0'];
		$insertdata1 = array(
			"Tvmaon5_r3vwM0" => $tiendadong,
		);
		$wheredata1 = array(
			"Tvmaon5_id" => $val['id']
		);
		updatedb($conn, "tvmaon5", array("where" => $wheredata1, "data" => $insertdata1));
	}

	$insertdata = array(
		"T1rqwvk_7l2iVU" => $infopt['T1rqwvk_7l2iVU'] + $tongtienpb,
		"T1rqwvk_i3MOQx" => $infopt['T1rqwvk_lcAKLV'] - $infopt['T1rqwvk_7l2iVU'] - $tongtienpb
	);
	$wheredata = array(
		"T1rqwvk_id" => $_GET['id']
	);
	updatedb($conn, "t1rqwvk", array("where" => $wheredata, "data" => $insertdata));

	/*if ($idpb == null) {
		$idpb = 0;
	}*/
	echo json_encode($arrgui);
	exit;
}

if (isset($_GET['id'])) {
	$id = $_GET['id'];

}

$infopt = select_info($conn, "SELECT * FROM t1rqwvk WHERE t1rqwvk_id = '" . $id . "'");

?>
<style>
    #thongtinchung {
        border: 1px solid #ccc;
        border-radius: 16px;
        padding: 10px 16px;
    }

    .thongtin {
        border: 1px solid #ccc;
        border-radius: 16px;
        text-align: center;
    }
</style>
<div class="container">
    <div>
        <center>
            <h3>Thông tin phiếu thu</h3>
        </center>
    </div>
    <div class="row" id="thongtinchung">
        <div class="col-lg-3">
            <p><b>Số phiếu</b></p>
            <p class="thongtin"><b> <?= $infopt['T1rqwvk_hQlrMb'] ?></b></p>
        </div>
        <div class="col-lg-3">
            <p><b>Ngày thu</b></p>
            <p class="thongtin"><b><?= $infopt['T1rqwvk_FQmbUW'] ?></b></p>
        </div>
        <div class="col-lg-3">
            <p><b>Số tiền đã thu</b></p>
            <p class="thongtin"><b><span id="tongtien"><?= number_format($infopt['T1rqwvk_lcAKLV']) ?></span></b></p>
        </div>
        <div class="col-lg-3">
            <b><p>Số tiền đã phân bổ</p></b>
            <p class="thongtin"><b><span id="sotiendapb"><?= number_format($infopt['T1rqwvk_7l2iVU']) ?></span></b></p>
        </div>
        <div class="col-lg-3">
            <b><p>Số chưa phân bổ</p></b>
            <p class="thongtin"><b><span id="sotienpb"><?= number_format($infopt['T1rqwvk_i3MOQx']) ?></span></b></p>
        </div>
    </div>
    <br>
    <div class="row">

        <table id="bang1" class="table table-bordered table-striped table-hover text-center">
            <thead>
            <tr>
                <th>STT</th>
                <th>Mã học viên</th>
                <th>Tên học viên</th>
                <th>Lớp</th>
                <th>Học phí</th>
                <th>Số tiền đã đóng</th>
                <th>Còn lại</th>
                <th>Phân bổ</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$lisths = select_list($conn, "SELECT * FROM t1h5lm7 LEFT JOIN tvmaon5 ON T1h5lm7_id = Tvmaon5_enbza6 LEFT JOIN tnuacp9 ON Tnuacp9_id = Tvmaon5_pK6EBN  WHERE Tvmaon5_pK6EBN = '" . $infopt['T1rqwvk_rcq4Mg'] . "' AND Tvmaon5_3sVeUr = 240");

			$i = 0;
			foreach ($lisths as $hs) {
				$tiendapb = select_info($conn, "select sum(Tblz1re_SBDlEb) as dadong from tblz1re left join tvmaon5 on Tvmaon5_id = Tblz1re_oI5l8A where Tvmaon5_id =" . $hs['Tvmaon5_id']);
				$i++;
				?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $hs['Tnuacp9_uNqLzC'] ?></td>
                    <td><?= $hs['Tnuacp9_U1bpXv'] ?></td>
                    <td><?= $hs['T1h5lm7_V1n5lK'] ?></td>
                    <td><?= number_format($hs['Tvmaon5_kyI1Xe']) ?></td>
                    <td><?= number_format($tiendapb['dadong']) ?></td>
                    <td><span name="conlai<?= $hs['Tvmaon5_id'] ?>"
                              id="conlai<?= $hs['Tvmaon5_id'] ?>"><?= number_format($hs['Tvmaon5_kyI1Xe'] - $tiendapb['dadong']) ?></span>
                    </td>
                    <td width="50"><input type="text" class="form-control" id="<?= $hs['Tvmaon5_id'] ?>" name="tienpb"
                                          onchange="tongtienphanbo(<?= $hs['Tvmaon5_id'] ?>)" value="0"
                                          onkeyup="addcomma1(this)"></td>
                </tr>
			<?php } ?>

            </tbody>

        </table>
        <input type="hidden" value="[]" id="tongtienpb" name="tongtienpb">
        <div class="row">
            <center>
                <button type="submit" class="btn btn-success" onclick="xacnhanabc()">Xác nhận</button>
            </center>
        </div>
        <script src="js/makealert.js">

        </script>
        <script>
            function tongtienphanbo(ele) {
                var tienpb = rmcomma($('#' + ele).val());
                var conlai = rmcomma($('#conlai' + ele).html());
                if (tienpb * 1 > conlai * 1) {
                    alert("Tiền phân bổ không được lớn hơn số tiền còn lại");
                    $('#' + ele).val(0);

                    tongtienphanbo(ele);
                }
                else {
                    var mang = JSON.parse($('#tongtienpb').val()); //???
                    if (mang.length > 0) {
                        var check = 0;
                        for (var i = 0; i < mang.length; i++) {
                            if (mang[i].id == ele) {
                                check = 1;
                            }
                        }
                        if (check == 1) {
                            for (var i = 0; i < mang.length; i++) {
                                if (mang[i].id == ele) {
                                    mang[i].phanbo = tienpb;

                                }
                            }
                        }
                        else {
                            mang.push({'id': ele, 'phanbo': tienpb});
                        }
                    }
                    else {
                        mang.push({'id': ele, 'phanbo': tienpb});
                    }
                    $('#tongtienpb').val(JSON.stringify(mang)); //???
                    $.ajax({
                        url: "/tb5oazq?id=<?= $id ?>",
                        dataType: "text",
                        data: {mang: mang},
                        type: "POST",
                        success: function (data) {
                            $('#sotienpb').html(data);
                        },
                        error: function () {
                        }
                    });
                    $.ajax({
                        url: "/tb5oazq?id=<?= $id ?>",
                        dataType: "text",
                        data: {mang2: mang},
                        type: "POST",
                        success: function (data) {
                            $('#sotiendapb').html(data);
                        },
                        error: function () {
                        }
                    });
                }
            }

            function xacnhanabc() {
                var tongtienpb = $('#tongtienpb').val();
                var sotienpb = parseInt(rmcomma($('#sotienpb').html()));

                if (sotienpb < 0) {
                    makeAlertright("Không đc phép phân bổ lớn hơn số tiền còn lại của phiếu thu ", 7000);
                    return;
                }

                $.ajax({
                    url: "/tb5oazq?id=<?= $id ?>&sbmxn",
                    dataType: "json",
                    data: {tongtienpb: tongtienpb},
                    type: "POST",
                    success: function (data) {
                        if (data == null) {
                            makeAlertright("Không có số tiền phân bổ", 5000);
                        } else {
                            makeSAlertright("thêm thành công", 5000);
                            $.each(data, function (key, value) {
                                window.open('/tycp81a?id=' + value, '_blank');
                            });
                        }
                    },
                    error: function () {
                    }
                });
            }
        </script>
    </div>
</div>