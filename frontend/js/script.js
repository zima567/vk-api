// script.js
$(document).ready(function(){
    //handle show-hide of components
    $("#nav-signup").click(function(){
      $(".component").hide();
      $("#sec-signup").show();
    });

    $("#nav-login").click(function(){
        $(".component").hide();
        $("#sec-login").show();
    });

    $("#nav-add-product").click(function(){
        $(".component").hide();
        $("#sec-add-product").show();
    });

    $("#nav-catalogue").click(function(){
        $(".component").hide();
        $("#sec-section-catalogue").show();
    });

    //handle notification

    //handle signup
    $("#signupSubmit").click(function(){
        var login = $("#signup-login").val();
        var password = $("#signup-password").val();
        
        var userData = {
          "login": login,
          "password": password
        };
    
        $.ajax({
          url: '/api/register',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(userData),
          success: function(response) {
            console.log('POST request successful:', response);
            // Handle response as needed
            var msg = "Congratulations new account created!!!<br>\
            User id:" + response.id + "<br>\
            User login: " + response.login;

            $('#noti').html("");
            $('#noti').append(msg);
          },
          error: function(error) {
            // Handle error or display an error message
            $('#noti').html("");
            $('#noti').append("Error in POST request: " + error.responseJSON.error);
            console.error('Error in POST request:', error);
          }
        });
    });
  
        //handle login
    $("#loginSubmit").click(function(){
        var login = $("#login-login").val();
        var password = $("#login-password").val();
        
        var userData = {
          "login": login,
          "password": password
        };
    
        $.ajax({
          url: '/api/auth',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(userData),
          success: function(response) {
            //Save token in browser local storage
            localStorage.setItem('token-api-vk', response.token);
            console.log('POST request successful:', response);
            // Handle response as needed
            var msg = "Congratulations you are successfully logged in!!!<br>";
            var token =  '<span>Token: '+ response.token.substring(0, 10)+ '......'+
                          response.token.substring(response.token.length - 10)+'</span';

            $('#noti').html("");
            $('#noti').append(msg);
            $('#noti').append(token);
          },
          error: function(error) {
            // Handle error or display an error message
            $('#noti').html("");
            $('#noti').append("Error in POST request: " + error.responseJSON.error);
            console.error('Error in POST request:', error);
          }
        });
    });

    //Upload file to server
    //Then upload products info
    $('#addProductSubmit').click(function() {
      var fileInput = document.getElementById('ppicture');
      var title = $("#title").val();
      var price = $("#price").val();
      var description = $("#description").val();
      var storedToken = localStorage.getItem('token-api-vk');

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
                console.log('POST request successful:', response);
                // Handle response as needed
                var msg = "Congratulations you are successfully logged in!!!<br>";
    
                $('#noti').html("");
                $('#noti').append(msg);
              },
              error: function(error) {
                // Handle error or display an error message
                $('#noti').html("");
                $('#noti').append("Error in POST request: " + error.responseJSON.error);
                console.error('Error in POST request:', error);
              }
            });
  
          },
          error: function(xhr, status, error) {
            // Handle any AJAX errors
            console.log('Error uploading file: ' + error);
          }
        });

      } else {
        alert("Please login for authentication token");
      }
    });

    //query data from db
    $('#nav-catalogue').on('click', function() {
        // parameters optional
        const params = {
        };

        // Make the GET request using jQuery
        $.ajax({
          url: '/api/products',
          type: 'GET',
          data: params,
          success: function(data) {
              console.log(data);
              var products = data.products;
              //$("#catalogue").html("");
              products.forEach(product => {
                  let prodElement = '<div class="product-box">\
                  <img src="'+product.pictureLink+'" alt="Product Image">\
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
              console.error('There was a problem with the request:', error);
          }
       }); 
    });

  });
