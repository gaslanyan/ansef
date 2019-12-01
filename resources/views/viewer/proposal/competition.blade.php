@if(!empty($proposals))
    <table class="table table-responsive-md table-sm table-bordered display" id="example"
           style="width:100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Proposal Categories</th>
            <th>Title</th>
            <th>Account</th>
            <th>PI</th>
            <th>Score</th>
            <th>Rank</th>
            <th>State</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($proposals as $proposal)
            <tr>
                <td>{{$proposal['id']}}</td>
                <td><?php $categories = json_decode($proposal['categories']); echo getCategoriesNameByID($categories->parent). " - " . getCategoriesNameByID($categories->sub);?></td>
                <td>{{$proposal['title']}}</td>
                <td></td>
                <td></td>
                <td>{{$proposal['overall_score']}}</td>
                <td>{{$proposal['rank']}}</td>
                <td>{{$proposal['state']}}</td>
                <td><a href="{{action('Viewer\ProposalController@show',$proposal['id'])}}"
                       class="view" title="View"><i class="fa fa-eye"></i></a>
                         <a href="{{action('Viewer\ProposalController@generatePDF',$proposal['id'])}}"
                           title="Download"
                           class="add_honors"><i class="fa fa-download"></i>
                        </a>
                       </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
<script>
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "pagingType": "full_numbers",
            // "order": [[0, "asc"]],
            // "columnDefs": [
            //     {
            //         "targets": [5],
            //         "searchable": false
            //     }
            // ]
            columnDefs: [{
                targets: [0],
                orderData: [0, 1]
            }, {
                targets: [1],
                orderData: [1, 0]
            }]
        });
        t.on('order.dt search.dt', function () {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });

</script>
