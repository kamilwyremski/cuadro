<!doctype html>
<html lang="{{ settings.lang }}">
<head>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="Keywords" content="{{ settings.keywords }}">
	<meta name="Description" content="{{ settings.description }}">
	<meta name="author" content="Kamil Wyremski - wyremski.pl">
	<title>{{ settings.title }}</title>
	<base href="{{ settings.base_url }}/">

	<!-- CSS style -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="views/{{ settings.template }}/materialize/css/materialize.min.css"/>
	<link rel="stylesheet" href="views/{{ settings.template }}/css/style.css"/>
	{% if settings.favicon %}<link rel="shortcut icon" href="{{ settings.favicon }}">{% endif %}
	{% if settings.code_style %}<style>{{ settings.code_style|raw }}</style>{% endif %}

	<!-- integration with Facebook -->
	{% if settings.logo_facebook  %}<meta property="og:image" content="{{ settings.logo_facebook }}">{% endif %}
	<meta property="og:description" content="{{ settings.description }}">
	<meta property="og:title" content="{{ settings.title }}">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{{ settings.title }}">
	<meta property="og:locale" content="{{ settings.facebook_lang }}">
	{% if settings.facebook_api %}<meta property="fb:app_id" content="{{ settings.facebook_api }}">{% endif %}

	<!-- other -->
	{% if settings.rss %}<link rel="alternate" type="application/rss+xml" href="{{ settings.base_url }}/php/rss.php{% if pagination.page_url.page %}?{{ pagination.page_url.page }}{% endif %}">{% endif %}
	{{ settings.code_head|raw }}

</head>
<body>

<nav id="menu" class="{{ settings.template_color }}">
	<div class="container">
		<div class="nav-wrapper">
			<a href="{{ settings.base_url }}" class="brand-logo">{% if settings.logo %}<img src="{{ settings.logo }}" alt="Logo">{% else %}{{ settings.header }}{% endif %}</a>
			<ul class="right hide-on-med-and-down">
				<li {% if controller_subpage=='main_page' %}class="active"{% endif %}><a href="{{ settings.base_url }}" title="{{ 'Home'|lang }}">{{ 'Home'|lang }}</a></li>
				<li {% if controller_subpage=='waiting_room' %}class="active"{% endif %}><a href="{{ path('waiting_room') }}" title="{{ 'Waiting Room'|lang }}">{{ 'Waiting Room'|lang }}</a></li>
				<li {% if controller_subpage=='top' %}class="active"{% endif %}><a href="{{ path('top') }}" title="{{ 'Top'|lang }}">{{ 'Top'|lang }}</a></li>
				{% if categories %}
					<li>
						<a class="dropdown-trigger" href="#!" data-target="dropdown_categories">{{ 'Categories'|lang }} <i class="material-icons right">arrow_drop_down</i></a>
						<ul id="dropdown_categories" class="dropdown-content">
							{% for item in categories %}
								<li><a href="{{ path('category',item.id,item.slug) }}" title="{{ item.name }}">{{ item.name }}</a></li>
							{% endfor %}
						</ul>
					</li>
				{% endif %}
				<li {% if controller=='add' %}class="active"{% endif %}><a href="{{ path('add') }}" title="{{ 'Add'|lang }}">{{ 'Add'|lang }}</a></li>
				{% if user.id %}
					<li>
						<a class="dropdown-trigger" href="#!" data-target="dropdown_account">{{ 'Account'|lang }} <i class="material-icons right">arrow_drop_down</i></a>
						<ul id="dropdown_account" class="dropdown-content">
							<li {% if controller=='my_files' %}class="active"{% endif %}><a href="{{ path('my_files') }}" title="{{ 'My files'|lang }}">{{ 'My files'|lang }}</a></li>
							<li {% if controller=='settings' %}class="active"{% endif %}><a href="{{ path('settings') }}" title="{{ 'Settings'|lang }}">{{ 'Settings'|lang }}</a></li>
							<li><a href="?logOut" title="{{ 'Log out'|lang }}">{{ 'Log out'|lang }}</a></li>
						</ul>
					</li>
				{% else %}
					<li {% if controller=='login' %}class="active"{% endif %}><a href="{{ path('login') }}" title="{{ 'Login'|lang }}">{{ 'Login'|lang }}</a></li>
				{% endif %}
			</ul>
			<a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
			<ul id="slide-out" class="sidenav">
				<li {% if controller_subpage=='main_page' %}class="active"{% endif %}><a href="{{ settings.base_url }}" title="{{ 'Home'|lang }}">{{ 'Home'|lang }}</a></li>
				<li {% if controller_subpage=='waiting_room' %}class="active"{% endif %}><a href="{{ path('waiting_room') }}" title="{{ 'Waiting Room'|lang }}">{{ 'Waiting Room'|lang }}</a></li>
				<li {% if controller_subpage=='top' %}class="active"{% endif %}><a href="{{ path('top') }}" title="{{ 'Top'|lang }}">{{ 'Top'|lang }}</a></li>
				{% if categories %}
					<li>
						<a class="dropdown-trigger" href="#!" data-target="dropdown_categories_mobile">{{ 'Categories'|lang }} <i class="material-icons right">arrow_drop_down</i></a>
						<ul id="dropdown_categories_mobile" class="dropdown-content">
							{% for item in categories %}
								<li><a href="{{ path('category',item.id,item.slug) }}" title="{{ item.name }}">{{ item.name }}</a></li>
							{% endfor %}
						</ul>
					</li>
				{% endif %}
				<li {% if controller=='add' %}class="active"{% endif %}><a href="{{ path('add') }}" title="{{ 'Add'|lang }}">{{ 'Add'|lang }}</a></li>
				{% if user.id %}
					<li {% if controller=='my_files' %}class="active"{% endif %}><a href="{{ path('my_files') }}" title="{{ 'My files'|lang }}">{{ 'My files'|lang }}</a></li>
					<li {% if controller=='settings' %}class="active"{% endif %}><a href="{{ path('settings') }}" title="{{ 'Settings'|lang }}">{{ 'Settings'|lang }}</a></li>
					<li><a href="?logOut" title="{{ 'Log out'|lang }}">{{ 'Log out'|lang }}</a></li>
				{% else %}
					<li {% if controller=='login' %}class="active"{% endif %}><a href="{{ path('login') }}" title="{{ 'Login'|lang }}">{{ 'Login'|lang }}</a></li>
				{% endif %}
			</ul>
		</div>
	</div>
</nav>

<main>
{% block content %}{% endblock %}
</main>
<footer class="page-footer {{ settings.template_color }}">
	<div class="container">
	  <div class="row">
		<div class="col l6 s12 grey-text text-lighten-4">
		  {{ settings.footer_top|raw }}
		</div>
		<div class="col l4 offset-l2 s12">
			<ul>
				<li><a class="grey-text text-lighten-3" href="{{ settings.base_url }}" title="{{ 'Home'|lang }}">{{ 'Home'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('waiting_room') }}" title="{{ 'Waiting Room'|lang }}">{{ 'Waiting Room'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('top') }}" title="{{ 'Top'|lang }}">{{ 'Top'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('random') }}" title="{{ 'Random'|lang }}">{{ 'Random'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('add') }}" title="{{ 'Add'|lang }}">{{ 'Add'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('rules') }}" title="{{ 'Terms of service'|lang }}">{{ 'Terms of service'|lang }}</a><li>
				<li><a class="grey-text text-lighten-3" href="{{ path('privacy_policy') }}" title="{{ 'Privacy policy'|lang }}">{{ 'Privacy policy'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('contact') }}" title="{{ 'Contact us'|lang }}">{{ 'Contact'|lang }}</a></li>
				<li><a class="grey-text text-lighten-3" href="{{ path('info') }}" title="{{ 'Info about us'|lang }}">{{ 'Info'|lang }}</a></li>
				{% if settings.rss %}<li><a class="grey-text text-lighten-3" href="php/rss.php{% if pagination.page_url.page %}?{{ pagination.page_url.page }}{% endif %}" title="{{ 'RSS feed'|lang }}" target="_blank">{{ 'RSS feed'|lang }}</a></li>{% endif %}
			</ul>
		</div>
	  </div>
	</div>
	<div class="footer-copyright">
	  <div class="container center-align">
		{{ settings.footer_bottom|raw}}
	  </div>
	</div>
</footer>

<a href="#" title="{{ 'Back to top'|lang }}" id="back_to_top" class="back_to_top_hidden hide-on-small-only"><i class="medium material-icons">arrow_upward</i></a>

<div id="cookies-message">{{ 'This site uses cookies, so that our service may work better.'|lang }}<a href="javascript:closeCookiesWindow();" id="accept-cookies-checkbox">{{ 'I accept'|lang }}</a></div>

{% if settings.rodo_alert %}
	<div id="rodo-message" class="modal modal-fixed-footer">
		<div class="modal-content">
			{{ settings.rodo_alert_text|raw }}
		</div>
		<div class="modal-footer">
			<a href="javascript:closeRodoWindow();" class="modal-action modal-close waves-effect btn-flat">{{ 'I agree to the processing my personal data'|lang }}</a>
		</div>
	</div>
{% endif %}

{% if settings.voice_only_logged and not user.id %}
 	<div id="modal_voice_only_logged" class="modal">
		<div class="modal-content">
			<p>{{ 'Only logged users can vote on posted materials'|lang }}</p>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect btn-flat">{{ 'Ok'|lang }}</a>
		</div>
	</div>
{% endif %}

{% if settings.facebook_side_panel %}
	<div id="facebook2_2" class="hide-on-small-only">
		<div id="facebook2_2_image"><img src="{{ settings.base_url }}/views/{{ settings.template }}/images/facebook-side.png" alt="Facebook" width="10" height="21"></div>
		<div class="fb-page" data-href="{{ settings.url_facebook }}" data-tabs="timeline" data-width="300" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="{{ settings.url_facebook }}" class="fb-xfbml-parse-ignore"><a href="{{ settings.url_facebook }}">Facebook</a></blockquote></div>
	</div>
{% endif %}

{% block javascript %}
	<script src="views/{{ settings.template }}/js/jquery-3.5.1.min.js"></script>
	<script src="views/{{ settings.template }}/materialize/js/materialize.min.js"></script>
	<script src="views/{{ settings.template }}/js/engine.js"></script>

	{% if settings.facebook_side_panel or settings.social_facebook or allow_comments_fb_file or allow_comments_fb_profile %}
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/{{ settings.facebook_lang|default(en_US) }}/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
	{% endif %}

	{{ settings.analytics|raw }}
{% endblock %}

{{ settings.code_body|raw }}

</body>
</html>
