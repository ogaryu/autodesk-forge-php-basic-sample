<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Authentication</div>
            <div id="forge-authentication" class="panel-body">
                <p>Request OAuth2 authentication</p>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/login" method="POST" class="ajaxform">
                            <button type="submit" class="btn btn-default">LOGIN</button>
                        </form>
                    </div>
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/logout" method="GET" class="ajaxform">
                            <button type="submit" class="btn btn-default">LOGOUT</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Data Management API</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Model Derivative API</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="panel panel-result">
            <div class="panel-heading">Request</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

        <div class="panel panel-result">
            <div class="panel-heading">Response</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>

        <div class="panel panel-result">
            <div class="panel-heading">Content</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>
    </div>

