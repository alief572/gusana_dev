 <div class="d-flex align-items-center justify-content-center ht-100v">
     <img src="<?= base_url('assets/background.jpg'); ?>" class="wd-100p ht-100p object-fit-cover" alt="">
     <div class="overlay-body bg-black-6 flex-column d-flex align-items-center justify-content-center">

         <?= Template::message(); ?>
         <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 rounded bd bd-white-2 bg-black-7">
             <div class="signin-logo tx-center tx-28 tx-bold tx-white"><span class="tx-normal">[</span> Login <span
                     class="tx-primary">App</span> <span class="tx-normal">]</span></div>
             <div class="tx-white-5 tx-center mg-b-40">Lorem ipsum dolor sit amet.</div>

             <form action="<?= base_url($this->uri->uri_string()); ?>" id="frm_login" name="frm_login" class="login"
                 method="POST">
                 <div class="form-group">
                     <input type="text" class="form-control fc-outline-dark" name="username" placeholder="Username"
                         value="<?= set_value('username') ?>" required autofocus>
                 </div><!-- form-group -->
                 <div class="form-group">
                     <input type="password" class="form-control fc-outline-dark" name="password" placeholder="Password"
                         value="" required>
                     <a href="" class="tx-primary tx-12 d-block mg-t-10">Forgot password?</a>
                 </div><!-- form-group -->
                 <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
             </form>
             <footer class="mt-5">
                 <div class="text-gray text-center">
                     <p>Copyright &copy; <?php echo $idt->nm_perusahaan."&nbsp;".date('Y')?></p>
                     <p>Halaman ini dimuat selama <strong>{elapsed_time}</strong> detik</p>
                 </div>
             </footer>
             <!-- <div class="mg-t-60 tx-center">Not yet a member? <a href="" class="tx-primary">Sign Up</a></div> -->
         </div><!-- login-wrapper -->
     </div><!-- overlay-body -->
 </div><!-- d-flex -->