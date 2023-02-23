<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.9.2
    </div>
    <strong>Copyright © {{ date('Y') }} MasterMundi <i class="fa fa-copyright"></i> .</strong> Todos os direitos reservados.
</footer>
<script>
    paceOptions = {
        // Disable the 'elements' source
        elements: false,

        // Only show the progress on regular and ajax-y page navigation,
        // not every request
        restartOnRequestAfter: false
    }
</script>
<!-- jQuery 2.2.0 -->
<script src="/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.min.js"></script>
<script src="/plugins/pace/pace.js"></script>

@yield('script')

</body>
</html>