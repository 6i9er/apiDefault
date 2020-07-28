@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><a href="javascript:void(0)">#</a></th>
                                    <th ><a href="javascript:void(0)">name</a></th>
                                    <th><a href="javascript:void(0)">email</a></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="usersTrs">
                                @foreach($users as $user)
                                    @include('home.userTableRow_tmpl',$user)
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pagination">
                                    <?php echo $users->render();  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script type="text/javascript">

        $(function() {
            $('.pagination li a[rel=next] , .pagination li a[rel=prev]').parent('li').remove();
            $('body').on('click', '.pagination a', function(e) {
                e.preventDefault();
                var currentPageInfo = getCurrentPage();
                $(this).parent('li').attr('class' , "active");
                var url = $(this).attr('href');
                getUsers(url);
                window.history.pushState("", "", url);
            });


            function getCurrentPage(removeBtns = 0){
                var currentPage = "";
                var currentPageUrl = "";
                $('.pagination li').each(function () {
                    if(removeBtns == "1"){
                        if($(this).attr('rel') == "next" ){
                            $(this).remove()
                        }
                    }else{
                        if ($(this).attr('class') == "active") {
                            currentPage = ($(this).children('span').text()) ? $(this).children('span').text() : $(this).children('a').text();
                            currentPageUrl = "{{url('users?page=')}}" + currentPage;
                            $(this).html("<a href='" + currentPageUrl + "'>" + currentPage + "</a>")
                        }
                        $(this).attr('class', "");
                    }
                });
                return {
                    "currentPage" : currentPage,
                    "currentPageUrl" : currentPageUrl
                }
            }
            // get the new page data
            function getUsers(url) {
                $.ajax({
                    url : url
                }).done(function (data) {
                    if(data['error'] == "0"){
                        $('#usersTrs').html(data['data']);
                    }
                }).fail(function () {
                    alert('Users could not be loaded.');
                });
            }
        });

    </script>

@endsection