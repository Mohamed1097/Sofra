
var urlSearchParams = new URLSearchParams(window.location.search);
var params = Object.fromEntries(urlSearchParams.entries());
$('.switch').change(function()
{
  var key=this;
  let url=$(this).attr('url');
  this.disabled=true;
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },  
        });
  $.ajax({  
    type: 'PUT',
    url: url,
    contentType: 'application/json',
    data:JSON.stringify(
      {[key.name]:key.checked}
    ),
    success: function(data) 
    {
      if(!data.status)
      {
        key.disabled=false;
        if(key.checked)
        {
          key.checked=false
        }
        else
        {
          key.checked=true
        }
        document.querySelector('.toast-body').textContent=data.message;
        $('.toast').toast('show',10000);
      }
      else
      {
        key.disabled=false;
        key.value=Object.values(data)[0];
        document.querySelector('.toast-body').textContent=data.message;
        $('.toast').toast('show',10000);
        if(key.checked)
        {
          key.parentElement.querySelector('label').textContent=key.getAttribute('v1')
        }
        else
        {
          key.parentElement.querySelector('label').textContent=key.getAttribute('v2')
        }
      }
  }
    });
});
var raw='';
$('.delete-btn').click(function()
{
  raw=this.parentElement.parentElement
  
  $('.modal-body').html('Are You Sure You Wanna Delete '+this.getAttribute('element'));
  $('.modal-footer .delete').attr('url',$(this).attr('url'));
})

$('.modal-footer .delete').click(function()
{
  $('#delete-modal').modal('hide')
  let url=this.getAttribute('url');
  let btn =this;
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },  
        });
  $.ajax({  
    type: 'DELETE',
    url: url,
    contentType: 'application/json',
    success:function(data)
    {
      console.log(data);
      document.querySelector('.toast-body').textContent=data.message;
      $('.toast').toast('show',1000000);
      if(data.status==1)
      {
       raw.remove(); 
      }
      else
      {
        console.log(data.status);
      }
      if (document.querySelectorAll('tr').length<3) 
      {
        url=window.location.pathname;
        if(typeof params !=='undefined')
        {
          url+='?';
         if (typeof params.page !=='undefined' ) 
         {
          if (params.page!=1) 
            params.page--;
          }
          keys=Object.keys(params);
          params=Object.values(params);
          params.forEach( function(param,index) {
            url+=keys[index]+"="+param+'&'
          });
        }
        window.location=url.substring(0, url.length - 1);
        
        
      }
    }
})
})
$('.search-by').change(function(event) {
	if(this.value==1)
	{
		this.parentElement.querySelector('.search')
		.setAttribute('placeholder', 'Enter The Phone')
	}
	else if (this.value==2) {
		this.parentElement.querySelector('.search')
		.setAttribute('placeholder', 'Enter The Email')
	}
	else
	this.parentElement.querySelector('.search')
		.setAttribute('placeholder', 'Search')
});
$('.search-btn').click(function(event) {

	let=url=window.location.href.split('?')[0];
	let searchBy=this.parentElement.querySelector('.search-by')
	let search=this.parentElement.querySelector('.search')
	if(searchBy.value==1||searchBy.value==2)
	{
		url+='?filter='+searchBy.value+'&keyword='+search.value;
		window.location=url;
	}
});
$('.select-all').change(function(event) {
        let options=this.parentElement.querySelectorAll('select option');
        if (this.checked==true) {
                options.forEach( function(element, index) {
                    element.selected=true;
                });
        }
        else
        {
            options.forEach( function(element, index) {
                    element.selected=false;
                });
        }
        });






