<div class="breadcrumbs">    
    <div class="pull-right">
        <?php if ($html && isset($source)): ?>
            <a href="javascript:;" class="btn-black" id="toggle">Toggle source</a>
        <?php endif ?>
        <?php if ($deletion_enabled): ?>
            <a href="javascript:;" class="btn-black" id="delete">Delete page</a>
        <?php endif ?>
        <?php if ($use_pastebin): ?>
            <a href="javascript:;" class="btn-black" id="create-pastebin" title="Create public Paste on PasteBin">Create public Paste</a>
        <?php endif; ?>
    </div>    

    <?php $path = array(); ?>
    <ul class="breadcrumb">
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="glyphicon glyphicon-home glyphicon-white"></i> /wiki</a>
        </li>
        <?php $i = 0; ?>

        <?php foreach ($parts as $part): ?>
            <?php $path[] = $part; ?>
            <?php $url = BASE_URL . "/" . join("/", $path) ?>
            <li>
                <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
                    <?php if (++$i == count($parts) && !$is_dir): ?>
                        <i class="glyphicon glyphicon-file glyphicon-white"></i>
                    <?php else: ?>
                        <i class="glyphicon glyphicon-folder-open glyphicon-white"></i>
                    <?php endif ?>
                    <?php echo $part; ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</div>

<?php if ($html): ?>
    <?php if ($use_pastebin): ?>
    <div id="pastebin-notification" class="alert" style="display:none;"></div>
    <?php endif; ?>
    <?php if ($deletion_enabled): ?>
    <div id="deletion-notification" class="alert" style="display:none;"></div>
    <div id="delete-confirmation" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <p>This page will be deleted permanently. Are you sure?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="deleteConfirmed">Delete page</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php endif; ?>
    <div id="render">
        <?php echo $html; ?>
    </div>
    <script>
        $('#render pre').addClass('prettyprint linenums');
        prettyPrint();

        $('#render a[href^="#"]').click(function(event) {
            event.preventDefault();
            document.location.hash = $(this).attr('href').replace('#', '');
        });
    </script>
<?php endif ?>

<?php if (isset($source)): ?>
    <?php if ($use_pastebin): ?>
    <div id="pastebin-notification" class="alert" style="display:none;"></div>
    <?php endif; ?>
    <div id="source">
        <?php if (ENABLE_EDITING): ?>
            <div class="alert alert-info">
                <i class="glyphicon glyphicon-pencil"></i> <strong>Editing is enabled</strong>. Use the "Save changes" button below the editor to commit modifications to this file.
            </div>
        <?php endif ?>

        <form method="POST" action="<?php echo BASE_URL . "/edit" ?>">
            <input type="hidden" name="ref" value="<?php echo base64_encode($page['file']) ?>">
            <textarea id="editor" name="source" class="form-control" rows="<?php echo substr_count($source, "\n") + 1; ?>"><?php echo $source; ?></textarea>

            <?php if (ENABLE_EDITING): ?>
                <div class="form-actions">
                    <input type="submit" class="btn btn-warning btn-sm" id="submit-edits" value="Save Changes">
                </div>
            <?php endif ?>
        </form>
    </div>

    <script>
        <?php if ($html): ?>
            CodeMirror.defineInitHook(function () {
                $('#source').hide();
            });
        <?php endif ?>

        var mode = false;
        var modes = {
            'md': 'markdown',
            'js': 'javascript',
            'php': 'php',
            'sql': 'text/x-sql',
            'py': 'python',
            'scm': 'scheme',
            'clj': 'clojure',
            'rb': 'ruby',
            'css': 'css',
            'hs': 'haskell',
            'lsh': 'haskell',
            'pl': 'perl',
            'r': 'r',
            'scss': 'sass',
            'sh': 'shell',
            'xml': 'xml',
            'html': 'htmlmixed',
            'htm': 'htmlmixed'
        };
        var extension = '<?php echo $extension ?>';
        if (typeof modes[extension] != 'undefined') {
            mode = modes[extension];
        }

        var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
            lineNumbers: true,
            lineWrapping: true,
            <?php if (USE_DARK_THEME): ?>
            theme: 'tomorrow-night-bright',
            <?php else: ?>
            theme: 'default',
            <?php endif; ?>
            mode: mode
            <?php if(!ENABLE_EDITING): ?>
            ,readOnly: true
            <?php endif ?>
        });

        $('#toggle').click(function (event) {
            event.preventDefault();
            $('#render').toggle();
            $('#source').toggle();
            if ($('#source').is(':visible')) {
                editor.refresh();
            }

        });

        <?php if ($deletion_enabled): ?>
        $('#delete').click(function (event) {
          $('#delete-confirmation').modal('show');
        });

        $('#deleteConfirmed').click(function (event) {
          deletePage();
          $('#delete-confirmation').modal('hide');
        });

        function deletePage() {
            var notification = $('#deletion-notification');
            notification.removeClass('alert-info alert-error').html('').hide();

            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL . '/?a=delete'; ?>',
                data: { ref: '<?php echo base64_encode($page['file']); ?>' },
                context: $(this)
            }).done(function(response) {                
                $(this).removeClass('disabled');

                if (response.status === 'ok') {
                    notification.addClass('alert-info').html('Page deleted. ').show();
                    window.location = response.url;
                } else {
                    notification.addClass('alert-error').html('Could not delete page.').show();
                }
            });
        };
        <?php endif ?>

        <?php if ($use_pastebin): ?>
        $('#create-pastebin').on('click', function (event) {
            event.preventDefault();

            $(this).addClass('disabled');

            var notification = $('#pastebin-notification');
            notification.removeClass('alert-info alert-error').html('').hide();

            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL . '/?a=createPasteBin'; ?>',
                data: { ref: '<?php echo base64_encode($page['file']); ?>' },
                context: $(this)
            }).done(function(response) {                
                $(this).removeClass('disabled');

                if (response.status === 'ok') {
                    notification.addClass('alert-info').html('Paste URL: ' + response.url).show();
                } else {
                    notification.addClass('alert-error').html('Error: ' + response.error).show();
                }
            });
        });
        <?php endif; ?>
    </script>
<?php endif ?>
