$(document).ready(function () {

	function Time() {

		var days = 3; // Количество дней, после которых сбрасываем счетчик
		var data = Date.parse('01/23/2017') // дата начала строго "месяц/день/год",
		data = new Date(data);
		data.setMinutes((-180 - data.getTimezoneOffset()), 0, 0);
		for (; (new Date).getTime() > data;) {
			data.setDate(data.getDate() + days + 1)
		}
		var a = data.getTime() - (new Date).getTime();

		window.setTimeout(Time, 1E3);
		var res = new Array(data.getFullYear(), data.getMonth(), data.getDate());

		return res;
	};
	var res = new Array();
	res = Time();

	var d = new Date(res[0], res[1], res[2], 23, 59);
	$('#countdown').countdown({ until: d, timezone: +3 });

	$('#countdown2').countdown({ until: d, timezone: + 3 });


	$('#btn1').colorbox({
		href: 'form1.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn2').colorbox({
		href: 'form7.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn3').colorbox({
		href: 'form3.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn4').colorbox({
		href: 'form4.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn5').colorbox({
		href: 'form5.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn6').colorbox({
		href: 'form6.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn7').colorbox({
		href: 'form8.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});
	$('#btn8').colorbox({
		href: 'form2.html',
		className: true
		//close: '<button id="cboxClose1"></button>'
	});

	// Callback

	var call1 = {
		href: 'callback.html',
		className: 'callback',
		width: '820px',
		height: '500px'
	}

	var call2 = {
		href: 'callback.html',
		className: 'callback',
		width: '100%',
		height: 'auto'
	}





	var obj = {
		rel: 'gal',
		title: function () {
			var url = $(this).find('img').attr('src');
			return '<a href="' + url + '" target="_blank"></a>';
		},
		width: '100%'
		// slideshow: true,
		// slideshowStart: '<button class="slStart">Start</button>',
		// slideshowStop: '<button class="slStop">Stop</button>'
	}

	var obj1 = {
		rel: 'gal',
		title: function () {
			var url = $(this).find('img').attr('src');
			return '<a href="' + url + '" target="_blank"></a>';
		}
		// slideshow: true,
		// slideshowStart: '<button class="slStart">Start</button>',
		// slideshowStop: '<button class="slStop">Stop</button>'
	}

	var objVideo = {
		iframe: true,
		innerWidth: 640,
		innerHeight: 390,
	}

	var objVideo1 = {
		iframe: true,
		innerWidth: '100%',
		innerHeight: 390,
	}

	if ($(window).width() <= 450) {
		$('a.gallery').colorbox(obj);
		$('a.video').colorbox(objVideo1);
	}
	else {
		$('a.gallery').colorbox(obj1);
		$('a.video').colorbox(objVideo);
	}

	if ($(window).width() <= 660) {
		$('#callback').colorbox(call2);
	}
	else {
		$('#callback').colorbox(call1);
	}

	if ($(window).width() <= 660) {
		$('#callback2, #callback3').colorbox(call2);
	}
	else {
		$('#callback2, #callback3').colorbox(call1);
	}


});

function callb(subj, form) {
	var name = $(form).find('#name').val();
	var phone = $(form).find('#phone').val();
	$.ajax({
		url: 'mail.php',
		type: 'post',
		data: { name: name, phone: phone, subj: subj },
		success: function (data) {
			$(form).html(data);
		}
	});
}


jQuery(document).ready(function ($) {

	$.mask.definitions['a'] = '[a-zA-Zа-яА-Я]';
	$.mask.definitions['='] = '[a-zA-Zа-яА-Я0-9]';
	$("#phone-form").mask("+7 (999) 999-99-99");
	$("#number-tc").mask("a999aa 99?9");
	$("#number-reg").mask("99==999999");


	$("#new_delivery").submit(function (e) {
		e.preventDefault();
		var form = $(this)
		$.post($(this).attr("action"), $(this).serialize(), function (data) {
			var parent = form.parent();
			var el = $("<h1>" + data.message + "</h1>")
			form.remove();
			parent.append(el);

		})

	})

	var messages = {
		serviceError: 'Ошибка сервиса. Попробуйте повторить ваш запрос позже.',
		status2msg: {
			ACTIVE: '<span style="color: green">Активный</span>',
			EXPIRED: '<span style="color: red">Закончился</span>',
			EXPIRING: '<span style="color: red">Закончился</span>',
			CANCELED: '<span style="color: red; font-weight: bold">АННУЛИРОВАН</span>'
		}
	};

	$("#check-pass-form").submit(function (e) {
		e.preventDefault();
		jQuery.post('get_passes.php', { "string": $("input[name='vehicle_number']").val() }, 'json')
			.done(function (data) {
				console.log(data);

				var table = jQuery("#pass-list");
				var data_block = $("#pass_info");
				table.hide();
				table.find("tbody tr").remove();
				data_block.text("");

				jQuery(data).each(function () {

					var tr = jQuery('<tr/>');

					tr.append(jQuery('<td/>').html(this.passInfo));
					tr.append(jQuery('<td/>').html(this.dateStart));
					tr.append(jQuery('<td/>').html(this.dateEnd));

					tr.append(jQuery('<td/>').html(this.zone));
					tr.append(jQuery('<td/>').html(messages.status2msg[this.status]));

					table.append(tr);
				});
				if (data.length > 0) {
					table.show();
					data_block.text("Всего пропусков: " + data.length)
				} else {

					if (data.message) {

						data_block.text(data.message)

					} else {

						data_block.text("Всего пропусков: " + data.length)

					}

				}
			})

	})
})
jQuery(document).ready(function ($) {
	$(".captcha").click(function () {
		$(this).removeAttr("src").attr("src", "fines_check.php?img=1" + new Date())
	})
	$(".flush_form").click(function () {
		$(this).parents('form').find("input[type=text], textarea").val("");
	});
	$(".upperinput").css("text-transform", "uppercase");
	$("#fines_check_form").submit(function (event) {
		event.preventDefault();
		var button = $(this).find("input[type='submit']");
		var result = $(this).serialize() + '&' + encodeURI(button.attr('name')) + '=' + encodeURI(button.attr('value'));
		jQuery.post('fines_check.php', result, 'json')
			.done(function (data) {
				$(".captcha").removeAttr("src").attr("src", "fines_check.php?img=1" + new Date())
				var data_block = $("#fines_info");
				var table = jQuery("#fines-list");
				table.hide();
				table.find("tbody tr").remove();
				data_block.text("");
				jQuery(data.data).each(function () {
					var tr = jQuery('<tr/>');
					tr.append(jQuery('<td/>').html(this.DateDecis));
					tr.append(jQuery('<td/>').html(this.DatePost));
					tr.append(jQuery('<td/>').html(this.Summa));
					tr.append(jQuery('<td/>').html(this.KoAPcode));
					table.append(tr);
				});
				if (data.data && data.data.length > 0) {
					table.show();
					data_block.text("Всего неоплаченных штрафов: " + data.data.length)
				} else {
					if (data.message) {
						data_block.text(data.message)
					} else {
						data_block.text("Неоплаченных штрафов не найдено");
					}
				}
			})
	})

	// hamburger
	$('.hamburger').on("click", function () {
		$('body').toggleClass('menu-open');

		if ($("body").hasClass("menu-open")) {
			$(".menu_hidden").fadeIn();
		} else {
			$(".menu_hidden").fadeOut();
		}
	});

	$('.menu_hidden').on("click", function () {
		$('body').toggleClass('menu-open');
		$(".menu_hidden").fadeOut();
	});

	//page up
	$(window).scroll(function () {
		if ($(this).scrollTop() > 1600) {
			$('.pageup').fadeIn();
		} else {
			$('.pageup').fadeOut();
		}
	});

	$("a.pageup").on('click', function () {
		var id_value = "";
		var id_value = this.id;

		console.log(id_value);
		smoothScroll(id_value);
	});

	// smooth scroll
	function smoothScroll(link) {
		$(link).click(function () {
			var _href = $(this).attr("href");
			$("html, body").animate({ scrollTop: ($(_href).offset().top - 80) + "px" });
			if (link != "a[href^='#wrapper']") {
				$('body').toggleClass('menu-open');
				$(".menu_hidden").fadeOut();
			}
			return false;
		});
	};

	smoothScroll("a[href^='#wrapper']");
	smoothScroll("a[href^='#ceni']");
	smoothScroll("a[href^='#bonus']");
	smoothScroll("a[href^='#check']");
	smoothScroll("a[href^='#review']");
	smoothScroll("a[href^='#footer']");

	//exitblock
	$(document).mouseleave(function (e) {
		if (e.clientY < 10) {
			$(".exitblock").fadeIn("fast");
		}
	});
	$(document).click(function (e) {
		if (($(".exitblock").is(':visible')) && (!$(e.target).closest(".exitblock .modaltext").length)) {
			$(".exitblock").remove();
		}
	});
})