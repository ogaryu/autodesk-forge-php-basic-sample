<?php $this->setLayoutVar('title', 'Home') ?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Authentication</div>
            <div id="forge-authentication" class="panel-body">
                <p>Request OAuth2 Authentication</p>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/login" method="POST" class="ajaxform" data-request-action="login">
                            <button type="submit" class="btn btn-default">LOGIN</button>
                        </form>
                    </div>
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/userprofile" method="GET" class="ajaxform" data-request-action="userprofile">
                            <button type="submit" class="btn btn-default">GET USER PROFILE</button>
                        </form>
                    </div>
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/logout" method="POST" class="ajaxform" data-request-action="logout">
                            <button type="submit" class="btn btn-default">LOGOUT</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Data Management API</div>
            <div class="panel-body">
                <p>Request A360 Data Information</p>
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group">
                        <form action="<?php echo $base_url; ?>/hubs" method="GET" class="ajaxform" data-request-action="hubs">
                            <button type="submit" class="btn btn-default dm-btn">GET HUB</button>
                        </form>
                    </div>
                </div>
                <div class="btn-toolbar" role="toolbar">
                    <div class="input-group">
                        <span class="input-group-addon" id="addon-hub-id">Hub Id</span>
                        <input type="text" id="hub-id" class="form-control" placeholder="Hub Id" aria-describedby="addon-hub-id">
                        <span class="input-group-btn">
                            <form action="<?php echo $base_url; ?>/projects/{#hub-id}" method="GET" class="ajaxform" data-request-action="projects">
                                <button type="submit" class="btn btn-default dm-btn">GET PROJECT</button>
                            </form>
                        </span>
                    </div>
                </div>
                <div class="btn-toolbar" role="toolbar">
                    <div class="input-group">
                        <span class="input-group-addon" id="addon-project-id">Project Id</span>
                        <input type="text" id="project-id" class="form-control" placeholder="Project Id" aria-describedby="addon-project-id">
                    </div>
                </div>
                <div class="btn-toolbar" role="toolbar">
                    <div class="input-group">
                        <span class="input-group-addon" id="addon-folder-id">Folder Id</span>
                        <input type="text" id="folder-id" class="form-control" placeholder="Folder Id" aria-describedby="addon-folder-id">
                        <span class="input-group-btn">
                            <form action="<?php echo $base_url; ?>/items/{#project-id}/folders/{#folder-id}" method="GET" class="ajaxform" data-request-action="items">
                                <button type="submit" class="btn btn-default dm-btn">GET ITEMS</button>
                            </form>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Viewer</div>
            <div class="panel-body">
                <p>Open Viewer</p>
                <div class="btn-toolbar" role="toolbar">
                    <div class="input-group">
                        <span class="input-group-addon" id="addon-derivative-id">Derivative Id</span>
                        <input type="text" id="derivative-id" class="form-control" placeholder="Derivative Id" aria-describedby="addon-derivative-id">
                        <span class="input-group-btn">
                            <div>
                                <button type="button" id="viewer-btn" class="btn btn-default dm-btn">OPEN VIEWER</button>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="panel panel-result">
            <div class="panel-heading">Client Request</div>
            <div class="panel-body" id="client-request-body">
                <pre></pre>
            </div>
        </div>

        <div class="panel panel-result">
            <div class="panel-heading">Server Request</div>
            <div class="panel-body" id="server-request-body">
                <pre>
                    <?php $this->view->endpoint ?>
                </pre>
            </div>
        </div>

        <div class="panel panel-result">
            <div class="panel-heading">Server Response</div>
            <div class="panel-body" id="server-response-body">
                <pre></pre>
            </div>
        </div>

        <div class="panel panel-result">
            <div class="panel-heading">Viewer Content</div>
            <div class="panel-body">
                <div id="viewer-div" style="position: relative; margin: 0; width: 100%; height: 400px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    </div>

