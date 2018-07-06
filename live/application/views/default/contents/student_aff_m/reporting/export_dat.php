<html>
<head>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/jszip-3.1.3/pdfmake-0.1.27/dt-1.10.15/b-1.3.1/b-colvis-1.3.1/b-flash-1.3.1/b-html5-1.3.1/b-print-1.3.1/datatables.min.js"></script>

<style>
    .textcent{
        text-align: center !important;
    }
    button.dt-button, div.dt-button, a.dt-button {
        background-image: none !important;
        background: white !important;
        border: 1px solid #000;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 14px;
    }
</style>

</head>
<body>

<h1>Export to:</h1>

<table id="large" class="display tablesorter" cellspacing="0" width="100%">
    <thead>
        <tr>
          <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">#</th>
          <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Group Name</th>
          <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Name</th>
          <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Email</th>
          <th class="bg-secondary uncek text-cl-white border-none" style="cursor:pointer;">Join Date</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $a = 1;
    $no = 1;
    $nowdate  = date("Y-m-d");

    foreach ($stu_dat as $d) {

      $join_date = gmdate("Y-m-d", $d->dcrea);

      $pt_name = $this->db->select('cl_name')
                 ->from('dsa_cert_levels')
                 ->where('cl_id',$d->cl_id)
                 ->get()->result();

        // echo "<pre>";print_r($token_balance);exit();
        ?>
        <tr>
          <td></td>
          <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->name; ?></td>
          <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->fullname; ?></td>
          <td style="text-align: left;padding-left: 5px !important;"><?php echo $d->email; ?></td>
          <td style="text-align: left;padding-left: 5px !important;"><?php echo $join_date; ?></td>
        </tr>
        <?php $no++; $a++; } ?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function() {
        var t = $('#large').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: 'Student Data'
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    title: 'Student Data'
                },
                {
                    extend: 'print',
                    text: 'Print'
                }

            ],
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            "bLengthChange": false,
            "searching": true,
            "bInfo" : true,
            "bPaginate": true,
            "pageLength": 10
        } );

        t.on( 'order.dt search.dt', function () {
           t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i + 1;
              t.cell(cell).invalidate('dom');
           } );
        } ).draw();

    } );
</script>

</body>
