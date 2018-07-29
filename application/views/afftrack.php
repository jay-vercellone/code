<?php include('header.php'); ?>

<div class="container ">

    <div class="panel panel-default">
        <div class="panel-body">

            <div style=" overflow:scroll">
                <table class="table table-striped table-bordered table-hover table-responsive dataTable" id="dataTable">
                    <thead>
                    <th>

                    <td>Offer ID</td>
                    <td>Offer Name</td>
                    <td>Revenue</td>
                    <td>Payout</td>
                    <td>Action</td>


                    </th>
                    </thead>

                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($offers as $offer):
                            ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $offer->program_pid; ?></td>
                                <td><?= $offer->program_name; ?></td>
                                <td>$<?= number_format($offer->program_adv_paying, 2); ?></td>
                                <td>$<?= number_format($offer->program_leadrate, 2); ?></td>
                                <td><button type="button"  onclick="showInApp('<?= $offer->program_pid; ?>')" class="button btn-sm btn-primary">Show</button>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </tbody>

                </table>
            </div>      
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function ()
    {
        var tableData = $('#dataTable').dataTable({
            "iDisplayLength": 100,
            "aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]]

        });



    });

    function showInApp(offerID) {

        $.get("<?= base_url('afftrack/showinapp') ?>/" + offerID, function (data, status) {
            alert(data);
        });
    }
</script>
</body>
</html>