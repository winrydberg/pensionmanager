<div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">Quick Search</h3>
    </div>

    <?php 
      $years = [];
      $currentyear = (int)date('Y');

      for($year=2010; $year<=$currentyear; $year++){
        array_push($years, $year);
      }
    ?>
    <div class="card-body">
        <form method="GET" action="{{url('/quick-search')}}">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Year</label>
                        <select class="form-control select2" name="year">
                            @foreach ($years as $y)
                              <option value="{{$y}}">{{$y}}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Month</label>
                        <select class="form-control select2" name="month">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-dark btn-flat btn-block"><i class="fa fa-search"></i> SEARCH</button>
        </form>
    </div>
</div>