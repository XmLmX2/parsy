<?php include $_ENV['VIEW_PATH'] . 'include/header.php'; ?>

<div class="container mt-5">
    <table id="list-table" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference ID</th>
                <th>Job name</th>
                <th>Job description</th>
                <th>Expires at</th>
                <th>Openings</th>
                <th>Company</th>
                <th>Profession</th>
            </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>

<!-- Datatables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<!-- Datatables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#list-table').DataTable({
            'paging': false,
            "processing": true,
            "serverSide": true,
            "ajax": '<?php echo $_ENV['WEBROOT'] . '?page=list&ajax_action=load_table'; ?>'
        });
    } );
</script>

<?php include $_ENV['VIEW_PATH'] . 'include/footer.php'; ?>
