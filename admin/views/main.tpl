<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="admin panel">
	<meta name="Description" content="Admin panel is a computer application that supports the creation and modification of digital content using a common user interface and thus usually supporting multiple users working in a collaborative environment. Created by Kamil Wyremski - wyremski.pl">
	<meta name="author" content="Kamil Wyremski">
	<title>{{ title }}</title>

	<link rel="stylesheet" href="views/css/bootstrap.min.css">
	<link rel="stylesheet" href="views/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="views/css/style.css">
	<link rel="shortcut icon" href="images/favicon.png"/>

	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.min.js"></script>
	<script src="js/bootstrap-datepicker.pl.min.js"></script>
	<script src="js/ckeditor/ckeditor.js"></script>
	<script src="js/engine_admin.js"></script>
</head>
<body>

{% block wrapper %}
<div id="wrapper">
  <nav class="main-nav navbar navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-nav">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="http://wyremski.pl" title="Kamil Wyremski - Web Design" target="_blank" id="logo"><img src="images/admin.png" alt="Admin Logo"></a>
    </div>
    <ul class="nav navbar-top-links navbar-right hidden-xs">
			<li><a href="?module=admin" title="{{ 'Admin Panel Settings'|lang }}"><span class="glyphicon glyphicon-user"></span> Admin</a></li>
			<li><a href="?logOut" title="{{ 'Log out'|lang }}"><span class="glyphicon glyphicon-log-out"></span> {{ 'Log out'|lang }}</a></li>
	  </ul>
    <div class="navbar-default sidebar" role="navigation" id="left-navigation">
      <div class="sidebar-nav navbar-collapse collapse">
		<ul class="nav" id="side-menu">
			<li {% if module=='index' %}class="active"{% endif %}><a href="{{ settings.base_url }}/{{ folder_admin }}" title="{{ 'Home'|lang }}"><span class="glyphicon glyphicon-home"></span> {{ 'Home'|lang }}</a></li>
			<li {% if module=='statistics' %}class="active"{% endif %}><a href="?module=statistics" title="{{ 'Statistics'|lang }}"><span class="glyphicon glyphicon-check"></span> {{ 'Statistics'|lang }}</a></li>
			<li {% if module=='files' %}class="active"{% endif %}><a href="?module=files" title="{{ 'Files'|lang }}"><span class="glyphicon glyphicon-list-alt"></span> {{ 'Files'|lang }}</a></li>
			<li {% if module=='users' %}class="active"{% endif %}><a href="?module=users" title="{{ 'Users'|lang }}"><span class="glyphicon glyphicon-user"></span> {{ 'Users'|lang }}</a></li>
			<li {% if module=='mailing' %}class="active"{% endif %}><a href="?module=mailing" title="{{ 'Mailing'|lang }}"><span class="glyphicon glyphicon-envelope"></span> {{ 'Mailing'|lang }}</a></li>
			<li {% if module=='categories' %}class="active"{% endif %}><a href="?module=categories" title="{{ 'Categories'|lang }}"><span class="glyphicon glyphicon-th-list"></span> {{ 'Categories'|lang }}</a></li>
			<li {% if module=='tags' %}class="active"{% endif %}><a href="?module=tags" title="{{ 'Tags'|lang }}"><span class="glyphicon glyphicon-th"></span> {{ 'Tags'|lang }}</a></li>
			<li {% if module=='boxes' %}class="active"{% endif %}><a href="?module=boxes" title="{{ 'Boxes'|lang }}"><span class="glyphicon glyphicon-list-alt"></span> {{ 'Boxes'|lang }}</a></li>
			<li>
				<a href="#" data-toggle="collapse" data-target="#submenu_contents" data-parent="#menu" class="collapsed">
					<span class="glyphicon glyphicon-edit"></span> {{ 'Contents'|lang }} <span class="caret"></span>
				</a>
				<div class="collapse" id="submenu_contents" style="height: 0px;">
					<ul class="nav nav-list">
						<li {% if module=='mails' %}class="active"{% endif %}><a href="?module=mails" title="{{ 'Mails'|lang }}">{{ 'Mails'|lang }}</a></li>
						<li {% if module=='info' %}class="active"{% endif %}><a href="?module=info" title="{{ 'Info'|lang }}">{{ 'Info'|lang }}</a></li>
					</ul>
				</div>
			</li>
			<li>
				<a href="#" data-toggle="collapse" data-target="#submenu_logs" data-parent="#menu" class="collapsed">
					<span class="glyphicon glyphicon-hdd"></span> {{ 'Logs'|lang }} <span class="caret"></span>
				</a>
				<div class="collapse" id="submenu_logs" style="height: 0px;">
					<ul class="nav nav-list">
						<li {% if module=='logs_files' %}class="active"{% endif %}><a href="?module=logs_files" title="{{ 'Files'|lang }}">{{ 'Files'|lang }}</a></li>
						<li {% if module=='logs_users' %}class="active"{% endif %}><a href="?module=logs_users" title="{{ 'Users'|lang }}">{{ 'Users'|lang }}</a></li>
						<li {% if module=='logs_mails' %}class="active"{% endif %}><a href="?module=logs_mails" title="{{ 'Mails'|lang }}">{{ 'Mails'|lang }}</a></li>
						<li {% if module=='reset_password' %}class="active"{% endif %}><a href="?module=reset_password" title="{{ 'Reset password'|lang }}">{{ 'Reset password'|lang }}</a></li>
					</ul>
				</div>
			</li>
			<li>
				<a href="#" data-toggle="collapse" data-target="#submenu_settings" data-parent="#menu" class="collapsed">
					<span class="glyphicon glyphicon-asterisk"></span> {{ 'Settings'|lang }} <span class="caret"></span>
				</a>
				<div class="collapse" id="submenu_settings" style="height: 0px;">
					<ul class="nav nav-list">
						<li {% if module=='settings_automation' %}class="active"{% endif %}><a href="?module=settings_automation" title="{{ 'Automation'|lang }}">{{ 'Automation'|lang }}</a></li>
						<li {% if module=='settings_black_list' %}class="active"{% endif %}><a href="?module=settings_black_list" title="{{ 'Black list'|lang }}">{{ 'Black list'|lang }}</a></li>
						<li {% if module=='settings_appearance' %}class="active"{% endif %}><a href="?module=settings_appearance" title="{{ 'Appearance'|lang }}">{{ 'Appearance'|lang }}</a></li>
						<li {% if module=='settings_social_media' %}class="active"{% endif %}><a href="?module=settings_social_media" title="{{ 'Social Media'|lang }}">{{ 'Social Media'|lang }}</a></li>
						<li {% if module=='settings_ads' %}class="active"{% endif %}><a href="?module=settings_ads" title="{{ 'Ads'|lang }}">{{ 'Ads'|lang }}</a></li>
						<li {% if module=='settings' %}class="active"{% endif %}><a href="?module=settings" title="{{ 'General settings'|lang }}">{{ 'General settings'|lang }}</a></li>
					</ul>
				</div>
			</li>
			<li class="visible-xs-block"><a href="?module=admin" title="{{ 'Admin Panel Settings'|lang }}"><span class="glyphicon glyphicon-user"></span> {{ 'Admin Panel Settings'|lang }}</a></li>
			<li class="visible-xs-block"><a href="?logOut" title="{{ 'Log out'|lang }}"><span class="glyphicon glyphicon-log-out"></span> {{ 'Log out'|lang }}</a></li>
		</ul>
      </div>
    </div>
  </nav>
  <div id="page-wrapper">

		{% if _ADMIN_TEST_MODE_ %}<p class="text-danger"><br><br><b>{{ 'Demo version of the Admin Panel. Editing functions are disabled'|lang }}</b></p>{% endif %}

		{% block content %}

		{% endblock %}

  </div>
</div>

{% endblock %}

<div id="cookies-message-container"><div id="cookies-message">{{ 'This site uses cookies, so that our service may work better.'|lang }}<a href="javascript:WHCloseCookiesWindow();" id="accept-cookies-checkbox">{{ 'I accept'|lang }}</a></div></div>

<div class="modal fade" id="roxySelectFile" tabindex="-1" role="dialog" aria-labelledby="Select file">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			 <div class="modal-body">
				<iframe frameborder="0" allowtransparency="true"></iframe>
			</div>
		</div>
	</div>
</div>

</body>
</html>
