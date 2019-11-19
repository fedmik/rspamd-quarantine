
var searchable = [
	"sender",
	"rcpt",
	"timestamp",
	"ip",
	"qid"
];
var conditionscount = 0;
var searchstate = Cookies.getJSON("searchstate");
var conditions = '';
var selectfields = "<select class = 'form-control'>";
searchable.forEach(function(element){
	selectfields += "<option value='"+element+"'>"+element+"</option>";
});
selectfields += "</select>";

jQuery('#filterbar').hide();
if(typeof(Cookies.get("filteropened")) != "undefined" && Cookies.get("filteropened") == 1)
{
	jQuery('#filterbutton').removeClass('arrowdown');
	jQuery('#filterbutton').addClass('arrowup');
	jQuery('#filterbar').show();
}

function buildQuerystring()
{
	var querystring = '';
	var searchstate = {};
	jQuery("#conditions>div").each(function(){
		var name = jQuery(this).find("input").attr("name");
		if(name == 'timestampstart' || name == 'timestampend')
		{
			searchstate['timestamp'] = {};
			var valuestart = jQuery(this).find("input[name='timestampstart']").val();
			var valueend = jQuery(this).find("input[name='timestampend']").val();
			if(valuestart)
			{
				if(!querystring)
				{
					querystring = "select id, timestamp, qid, sender, rcpt, ip, score, action from data where timestamp >= '"+valuestart+"'";
				}
				else
				{
					querystring += " and timestamp >= '"+valuestart+"'";
				}
				searchstate['timestamp']['start'] = valuestart;
			}
			if(valueend)
			{
				if(!querystring)
				{
					querystring = "select id, timestamp, qid, sender, rcpt, ip, score, action from data where timestamp <= '"+valueend+"'";
				}
				else
				{
					querystring += " and timestamp <= '"+valueend+"'";
				}
				searchstate['timestamp']['end'] = valueend;
			}
		}
		else
		{
			var value = jQuery(this).find("input").val();
			if(value)
			{
				if(!querystring)
				{
					querystring = "select id, timestamp, qid, sender, rcpt, ip, score, action from data where " + name + " like '%"+value+"%'";
				}
				else
				{
					querystring += " and " + name + " like '%" + value + "%'";
				}
				searchstate[name] = value;
			}
		}
	});
	if(querystring)
	{
		querystring += " order by timestamp desc";
	}
	jQuery("#searchquery").html(querystring);
	jQuery("#searchqueryinput").val(querystring);
	if(searchstate)
	{
		Cookies.set('searchstate', searchstate);
	}
}

if(typeof(searchstate) != "undefined" && !jQuery.isEmptyObject(searchstate))
{
	jQuery.each(searchstate, function(index, value){
		var istimestamp = false;
		var selectfields = "<select class = 'form-control'>";
		searchable.forEach(function(element){
			if(index == element)
				selectfields += "<option selected value='"+element+"'>"+element+"</option>";
			else
				selectfields += "<option value='"+element+"'>"+element+"</option>";
		});
		selectfields += "</select>";
		if(index == 'timestamp')
			istimestamp = true;
		var inputfield = '';
		if(istimestamp)
		{
			inputfield = `
				<div class = "col-auto timestampmark">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">Start:</div>
						</div>
						<input class = "conditionvalue form-control" type="date" name='`+index+`start' value='`+value['start']+`'>
					</div>
				</div>
				<div class = "col-auto timestampmark">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">End:</div>
						</div>
						<input class = "conditionvalue form-control" type="date" name='`+index+`end' value='`+value['end']+`'>
					</div>
				</div>
			`;
		}
		else
		{
			inputfield = `<div class = "col-auto"><input class = "conditionvalue form-control" type="text" name='`+index+`' value='`+value+`'></div>`;
		}
		conditions += `
			<div id = condition`+conditionscount+` class = "form-row">
			<div class = "col-auto">
			`+selectfields+`
			</div>
			`+inputfield+`
			<div class = "col-auto">
			<button class = "removecondition btn btn-primary">remove</button>
			</div>
			</div>
		`;
		conditionscount++;
	});
}
else
{
	conditions += `
		<div id = condition`+conditionscount+` class = "form-row">
		<div class = "col-auto">
		`+selectfields+`
		</div>
		<div class = "col-auto">
		<input class = "conditionvalue form-control" type="text" name='' value=''>
		</div>
		<div class = "col-auto">
		<button class = "removecondition btn btn-primary">remove</button>
		</div>
		</div>
	`;
	conditionscount++;
}
jQuery("#conditions").html(conditions);
buildQuerystring();


jQuery(".addcondition").click(function(){
	var newcondition = `
		<div id = condition`+conditionscount+` class = "form-row">
		<div class = "col-auto">
		`+selectfields+`
		</div>
		<div class = "col-auto">
		<input class = "conditionvalue form-control" type="text" name='' value=''>
		</div>
		<div class = "col-auto">
		<button class = "removecondition btn btn-primary">remove</button>
		</div>
		</div>
	`;
	jQuery("#conditions").append(newcondition);
	conditionscount++;
});
jQuery("#clearsearchform").click(function(){
	Cookies.remove("searchstate");
	location.href = '/';
});
jQuery("#conditions").on('click', '.removecondition', function(){
	jQuery(this).parent().parent().remove();
	buildQuerystring();
});
jQuery("#conditions").on('change paste keyup', '.conditionvalue', function(){
	if(!jQuery(this).attr("name"))
		jQuery(this).attr("name", jQuery(this).parent().parent().find("select").val());
	buildQuerystring();
});
jQuery("#conditions").on('change', 'select', function(){
	if(jQuery(this).val() == "timestamp")
	{
		var inputfield = `
			<div class = "col-auto timestampmark">
				<div class="input-group">
					<div class="input-group-prepend ">
						<div class="input-group-text">Start:</div>
					</div>
					<input class = "conditionvalue form-control" type="date" name='`+jQuery(this).val()+`start' value=''>
				</div>
			</div>
			<div class = "col-auto timestampmark">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">End:</div>
					</div>
					<input class = "conditionvalue form-control" type="date" name='`+jQuery(this).val()+`end' value=''>
				</div>
			</div>
		`;
		jQuery(this).parent().parent().find('.conditionvalue').parent().remove();
		jQuery(this).parent().parent().find('.timestampmark').remove();
		jQuery(this).parent().after(inputfield);
	}
	else
	{
		var inputfield = `
			<div class = "col-auto">
			<input class = "conditionvalue form-control" type="text" name='`+jQuery(this).val()+`' value=''>
			</div>
		`;
		jQuery(this).parent().parent().find('.conditionvalue').parent().remove();
		jQuery(this).parent().parent().find('.timestampmark').remove();
		jQuery(this).parent().after(inputfield);
	}
	buildQuerystring();
});

jQuery('#filterbutton').click(function(){
	jQuery('#filterbar').slideToggle('fast');
	if(jQuery(this).hasClass('arrowdown'))
	{
		jQuery(this).removeClass('arrowdown');
		jQuery(this).addClass('arrowup');
		Cookies.set('filteropened', 1);
	}
	else
	{
		jQuery(this).removeClass('arrowup');
		jQuery(this).addClass('arrowdown');
		Cookies.set('filteropened', 0);
	}
});
