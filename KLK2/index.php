<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLK-LOGIN</title>
    <link rel="stylesheet" href="./css/style5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>


    <header class="header-container">
      <div class="logoh">
        <img src="./assets/klk-banner.png" alt="KLK Logo">
      </div>
    </header>

    <section class="vh-100" style="background-color:rgb(39, 40, 41);">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
              <div class="card-body p-5 text-center" >
              <form id="loginForm">
                <h1 class="mb-5" style="font-family: 'Poppins', sans-serif;">KLK</h1>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" id="typeEmailX-2" placeholder="email" class="form-control form-control-lg" name="email"/>
                  <label class="form-label" for="typeEmailX-2"></label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" id="typePasswordX-2" placeholder="password" class="form-control form-control-lg" name="password"/>
                  <label class="form-label" for="typePasswordX-2"></label>
               </div>
               <div id="loginError" style="color: red; margin-top: 10px;"></div>
                <br>
               <button class="butt"  type="submit">Submit</button>
              </form>

                
                
                    <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? 
                      <a href="#!" class="link" style="color: #5a5e9a;">Register</a></p>
              
          
                <div class="text-white mb-3 mb-md-0">
                   KLK © 2025. All rights reserved.
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="./js/script.js"></script>
</body>
</html>
