@section("footer")
    <footer class="page-footer">
        <div class="container">
            <div class="footer-main">
                <div class="row">
                    <div class="col l6 s12">
                        <div class="col s12 center">
                            <p><img src="/assets/img/logo-white.png" alt=""></p>
                        </div>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <p class="grey-text text-lighten-3">{{ config('app.name') }} is not a Company. Yet.</p>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="">THESE</a></li>
                            <li><a class="grey-text text-lighten-3" href="">NOT</a></li>
                            <li><a class="grey-text text-lighten-3" href="">DROIDS</a></li>
                            <li><a class="grey-text text-lighten-3" href="">LOOKING</a></li>

                            <li><a class="grey-text text-lighten-3" href="">ARE</a></li>
                            <li><a class="grey-text text-lighten-3" href="">THE</a></li>
                            <li><a class="grey-text text-lighten-3" href="">YOU'RE</a></li>
                            <li><a class="grey-text text-lighten-3" href="">FOR</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m4 offset-m4">
                        <div class="footer-totop">
                            <a id="return-to-top" href="javascript:;"><div class="totop-link bounce"><i class="material-icons">keyboard_arrow_up</i></div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="row">
                <div class="col s12">
                    <div class="footer-follow">
                        <p>FOLLOW US ON</p>
                        <ul>
                            <a href="https://www.facebook.com/"><li><i class="mdi mdi-facebook" aria-hidden="true"></i></li></a>
                            <a href="https://www.twitter.com/"><li><i class="mdi mdi-twitter" aria-hidden="true"></i></li></a>
                            <a href="https://www.instagram.com/"><li><i class="mdi mdi-instagram" aria-hidden="true"></i></li></a>
                        </ul>
                        <p>© {{ date('Y') }} {{ config('app.name') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@show