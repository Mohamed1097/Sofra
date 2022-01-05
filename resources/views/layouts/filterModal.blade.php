@inject('cities','App\Models\city')
@inject('foodCategories','App\Models\foodCategory')
  <div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Restaurants Filter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action={{route('admin.restaurants.index')}} method="GET">
            <div class="modal-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Restaurant Name</label>
                <input type="text" class="form-control input" id="exampleInputEmail1" placeholder="Enter Restaurant Name" name="name">
            </div>
            <div class="form-group">
                <label for="exampleDataList" class="form-label">City</label>
                <input class="form-control input" list="cities" id="exampleDataList" placeholder="Choose The City"  name="city">
                <datalist id="cities">
                  @foreach ($cities->pluck('name')->toArray() as $city)
                      <option value={{$city}}>
                  @endforeach
                </datalist>
          </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Phone</label>
                    <input type="text" class="form-control input" id="exampleInputEmail1" placeholder="Enter Phone" name="phone" >
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control input" id="exampleInputEmail1" placeholder="Enter Email" name="email" >
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">food Categories</label>
                <select class="selectpicker input form-control" multiple data-mdb-filter="true" name="foodCategories[]">
                  @foreach ($foodCategories->all() as $foodCategory)
                      <option value={{$foodCategory->id}}>{{$foodCategory->name}}</option>
                  @endforeach
                </select>
                </div> 
                <div class="col-sm-6">
                    <!-- radio -->
                    <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="active" name="active" value="1">
                        <label for="active">
                            Active
                        </label>
                      </div>
                      <div class="icheck-primary d-inline ml-2">
                        <input type="radio" id="active" name="active" value="0">
                        <label for="active">
                            De-active
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <!-- radio -->
                    <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="busy" name="busy" value="1">
                        <label for="busy">
                            Busy
                        </label>
                      </div>
                      <div class="icheck-primary d-inline ml-2">
                        <input type="radio" id="busy" name="busy" value="0">
                        <label for="busy">
                            Not Busy
                        </label>
                      </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="open" name="open" value="1">
                          <label for="open">
                              Opened
                          </label>
                        </div>
                        <div class="icheck-primary d-inline ml-2">
                          <input type="radio" id="open" name="open" value="0">
                          <label for="open">
                              Closed
                          </label>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="warned" name="warned" value="1">
                          <label for="warned">
                            Warned
                          </label>
                        </div>
                        <div class="icheck-primary d-inline ml-2">
                          <input type="radio" id="warned" name="warned" value="0">
                          <label for="warned">
                              Not Warned
                          </label>
                        </div>
                    </div>
                  </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
        </form>
        </div>
      </div>
    </div>
  </div>