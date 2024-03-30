// script.js
$(document).ready(function(){
    //handle show-hide of components
    $("#nav-signup").click(function(){
        optionDisplay("nav-signup", "sec-signup");
    });

    $("#nav-login").click(function(){
        optionDisplay("nav-login","sec-login");
    });

    $("#nav-add-product").click(function(){
        optionDisplay("nav-add-product", "sec-add-product");
    });

    $("#nav-catalogue").click(function(){
        optionDisplay("nav-catalogue", "sec-section-catalogue");
    });

    $("#nav-filter").click(function(){
      optionDisplay("nav-filter", "sec-filter");
    });

    //handle signup
    $("#signupSubmit").on('click', function(){
        var login = $("#signup-login").val();
        var password = $("#signup-password").val();
        
        if(login.length > 0 && password.length > 0) {
          var userData = {
            "login": login,
            "password": password
          };
      
          $.ajax({
            url: '/api/register',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(userData),
            beforeSend: function() {
              showLoader(true);
            },
            success: function(response) {
              //Empty fields
              $("#signup-login").val("");
              $("#signup-password").val("");
              //hide loader
              showLoader(false);
              // Handle response as needed
              var msg = "Congratulations new account created!!!<br>\
              User id:" + response.id + "<br>\
              User login: " + response.login;

              $('#noti').html("");
              $('#noti').append(msg);
              console.log('POST request successful:', response);
            },
            error: function(error) {
              showLoader(false);
              // Handle error or display an error message
              $('#noti').html("");
              $('#noti').append("Error in POST request: " + error.responseJSON.error);
              console.error('Error in POST request:', error);
            }
         });
       } else {
          var msg = "Error: All fields are required";
          $('#noti').html("");
          $('#noti').append(msg);
       }
    });
  
        //handle login
    $("#loginSubmit").click(function(){
        var login = $("#login-login").val();
        var password = $("#login-password").val();
        
        if(login.length > 0 && password.length > 0) {
          var userData = {
            "login": login,
            "password": password
          };
      
          $.ajax({
            url: '/api/auth',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(userData),
            beforeSend: function() {
              showLoader(true);
            },
            success: function(response) {
              //empty login fields
              $("#login-login").val("");
              $("#login-password").val("");
              showLoader(false);
              //Save token in browser local storage
              localStorage.setItem('token-api-vk', response.token);
              // Handle response as needed
              var msg = "Congratulations you are successfully logged in!!!<br>";
              var token =  '<span>Token: '+ response.token.substring(0, 10)+ '......'+
                            response.token.substring(response.token.length - 10)+'</span';
  
              $('#noti').html("");
              $('#noti').append(msg);
              $('#noti').append(token);
              console.log('POST request successful:', response);
            },
            error: function(error) {
              showLoader(false);
              // Handle error or display an error message
              $('#noti').html("");
              $('#noti').append("Error in POST request: " + error.responseJSON.error);
              console.error('Error in POST request:', error);
            }
          });
        } else {
          var msg = "Error: All fields are required";
          $('#noti').html("");
          $('#noti').append(msg);
        }
    });

    //Upload file to server
    //Then upload products info
    $('#addProductSubmit').click(function() {
      var fileInput = document.getElementById('ppicture');
      var title = $("#title").val();
      var price = $("#price").val();
      var description = $("#description").val();
      var storedToken = localStorage.getItem('token-api-vk');

      if(title.length > 0 && price.length > 0 && fileInput.files.length > 0) {
        var formData = new FormData();
        formData.append('pictureLink', fileInput.files[0]);
  
        if(storedToken) {
          $.ajax({
            url: 'upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(xhr) {
              // Include the Bearer token in the request headers
              xhr.setRequestHeader('Authorization', 'Bearer ' + storedToken);
              showLoader(true);
            },
            success: function(response) {
              // Handle the server response
              console.log(response);
              var userData = {
                "pictureLink": response.filePath,
                "title": title,
                "price": price,
                "description": description
              };
    
              $.ajax({
                url: '/api/product',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(userData),
                beforeSend: function(xhr) {
                  // Include the Bearer token in the request headers
                  xhr.setRequestHeader('Authorization', 'Bearer ' + storedToken);
                },
                success: function(response) {
                  //Empty form
                  $("#title").val("");
                  $("#price").val("");
                  $("#description").val("");

                  showLoader(false);
                  console.log('POST request successful:', response);
                  // Handle response as needed
                  var msg = "Congratulations new product has been added!!!<br>";
      
                  $('#noti').html("");
                  $('#noti').append(msg);
                },
                error: function(error) {
                  showLoader(false);
                  // Handle error or display an error message
                  $('#noti').html("");
                  $('#noti').append("Error in POST request: " + error.responseJSON.error);
                  console.error('Error in POST request:', error);
                }
              });
    
            },
            error: function(xhr, status, error) {
              showLoader(false);
              // Handle any AJAX errors
              console.log('Error uploading file: ' + error);
            }
          });
  
        } else {
          alert("Please login for authentication token");
        }
      } else {
        var msg = "Error: All fields are required";
        $('#noti').html("");
        $('#noti').append(msg);
      }
    });

    //query data from db
    $('#nav-catalogue').on('click', function() {
        // parameters optional
        const params = {
        };
        var storedToken = localStorage.getItem('token-api-vk');
        storedToken = storedToken ? storedToken : "abcd";

        // Make the GET request using jQuery
        $.ajax({
          url: '/api/products',
          type: 'GET',
          data: params,
          beforeSend: function(xhr) {
            // Include the Bearer token in the request headers
            xhr.setRequestHeader('Authorization', 'Bearer ' + storedToken);
            showLoader(true);
          },
          success: function(data) {
              showLoader(false);
              console.log(data);
              let products = data.products;
              $("#catalogue").html("");
              products.forEach(product => {
                  let prodElement = '<div class="product-box">';
		  let splitPictureLink = product.pictureLink.split("/");
		  let link = splitPictureLink[splitPictureLink.length - 1];
		  prodElement += '<img src="/'+link+'" alt="Product Image">\
                  <h3>'+product.title+'</h3>\
                  <p class="price">'+product.price+'</p>\
                  <p class="description">'+product.description+'</p>\
                  <span class="seller"> Seller: '+product.login+'</span>';
                  if(product.owner) {
                    prodElement += '<span class="tag">Owner</span>';
                  }
                  prodElement += '</div>';
                  $("#catalogue").append(prodElement);
              });
            
          },
          error: function(xhr, status, error) {
              showLoader(false);
              console.error('There was a problem with the request:', error);
          }
       }); 
    });

    //filer search
    $('#filerSubmit').on('click', function() {
      // parameters optional
      const params = {
      };
      var limit, offset, priceMin, priceMax, oderType, orderDirection;
      limit = $.trim($("#limit").val());
      params.limit = limit ? limit : 100;

      offset = $.trim($("#offset").val());
      params.offset = offset ? offset : 0;

      priceMin = $.trim($("#priceMin").val());
      params.priceMin = priceMin ? priceMin : 0;

      priceMax = $.trim($("#priceMax").val());
      params.priceMax = priceMax ? priceMax : 0;

      orderType = $.trim($("#order-type").val());
      params.orderType = orderType ? orderType : "login";

      orderDirection = $.trim($("#order-direction").val());
      params.orderDirection = orderDirection == "asc" ? 1 : 0;
      
      var storedToken = localStorage.getItem('token-api-vk');
      storedToken = storedToken ? storedToken : "abcd";
      console.log(params);
      // Make the GET request using jQuery
      $.ajax({
        url: '/api/products',
        type: 'GET',
        data: params,
        beforeSend: function(xhr) {
          // Include the Bearer token in the request headers
          xhr.setRequestHeader('Authorization', 'Bearer ' + storedToken);
          showLoader(true);
        },
        success: function(data) {
            showLoader(false);
            console.log(data);
            let products = data.products;
            $("#sec-filter").hide();
            $("#catalogue").html("");
            $("#sec-section-catalogue").show();
            products.forEach(product => {
                let prodElement = '<div class="product-box">';
                  let splitPictureLink = product.pictureLink.split("/");
                  let link = splitPictureLink[splitPictureLink.length - 1];
                  prodElement += '<img src="/'+link+'" alt="Product Image">\
                  <h3>'+product.title+'</h3>\
                  <p class="price">'+product.price+'</p>\
                  <p class="description">'+product.description+'</p>\
                  <span class="seller"> Seller: '+product.login+'</span>';
                  if(product.owner) {
                    prodElement += '<span class="tag">Owner</span>';
                  }
                  prodElement += '</div>';
                  $("#catalogue").append(prodElement);
            });
        },
        error: function(xhr, status, error) {
            showLoader(false);
            console.error('There was a problem with the request:', error);
        }
     }); 
  });

    //Functions definitions
    //Function to show and hide loader
    function showLoader(flag) {
      if(flag === true) {
        $("#loaderContainer").css("display","flex");
      } else {
        $("#loaderContainer").css("display","none");
      }
    }

    //empty notifications, hide other component
    function optionDisplay(idOption, idSection) {
        $(".component").hide();
        $(".header-section li").removeClass("active");
        $("#"+idOption).addClass("active");
        $("#"+idSection).show();
        $('#noti').html("");
    }

});
