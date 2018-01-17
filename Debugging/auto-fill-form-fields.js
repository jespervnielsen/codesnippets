var quotes = new Array("Lorem", "Ipsum", "dolor ", "Sit", "amet", "consectetur", "adipiscing", "elit", "sed", "eiusmod " );

jQuery('input[type="text"]').each(function() {
	var randno = Math.floor ( Math.random() * quotes.length );
	jQuery(this).val(quotes[randno] + Math.floor((Math.random() * 100000) + 1));
});

jQuery('input[name*="mobile"],input[name*="nr"]').each( function() {
	jQuery(this).val(Math.floor((Math.random() * 90000000) + 10000000));
});
jQuery('input[name*="zipcode"]').each( function() {
	jQuery(this).val(2400);
});
jQuery('input[name*="email"]').each( function() {
	jQuery(this).val('jvn+test' + Math.floor((Math.random() * 100000) + 10000) + '@peytz.dk' );
});
jQuery('input[name*="email_confirm"]').each( function() {
	jQuery(this).val(jQuery('input[name*="email"]').val() );
});
jQuery('input[name*="name"]').each(function() {
	var randno = Math.floor ( Math.random() * quotes.length );
	jQuery(this).val(quotes[randno] + Math.floor((Math.random() * 100000) + 1));
});
jQuery('input[id="cpr"]').each(function() {
	var day = Math.floor((Math.random() * 20) + 10);
	var month = Math.floor((Math.random() * 2) + 10);
	var year = Math.floor((Math.random() * 9) + 90);
	var secret = Math.floor((Math.random() * 1000) + 1000);
	jQuery(this).val(day +''+ month + '' + year + '' + secret );
});
jQuery('.page input[type="checkbox"]:first-of-type').each(function() {
	jQuery(this).prop('checked', 'checked');
});
$(function(){
    //removed duplicates form the following array
    $(jQuery.unique(
        //create an array with the names of the groups
        $('INPUT:radio')
            .map(function(i,e){
                return $(e).attr('name') }
            ).get()
    ))
    //interate the array (array with unique names of groups)
    .each(function(i,e){
        //make the first radio-button of each group checked
        $('INPUT:radio[name="'+e+'"]:first')
            .attr('checked','checked');
    });
});
jQuery('select option:nth-of-type(2)').each(function() {
	jQuery(this).prop('selected', 'selected').trigger("click");
});
var year=2017;
jQuery('.hasDatepicker').each(function() {
	var day = Math.floor((Math.random() * 20) + 10);
	var month = Math.floor((Math.random() * 2) + 10);
	jQuery( this ).datepicker( "setDate", day + '/' + month +'/'+year );
	jQuery(this).val(day + '-' + month + '-' + year );
	year = year+1;
});






jQuery('.ginput_container_text input[type="text"]').each(function() {
	var randno = Math.floor ( Math.random() * quotes.length );
	jQuery(this).val(quotes[randno] + Math.floor((Math.random() * 100000) + 1));
});
jQuery('.ginput_complex input[type="text"]').each(function() {
	var randno = Math.floor ( Math.random() * quotes.length );
	jQuery(this).val(quotes[randno] + Math.floor((Math.random() * 100000) + 1));
});
jQuery('.gfield_list_group .add_list_item').each(function() {
	var number_of_times = Math.floor((Math.random() * 3) + 1);
	// var loop = setInterval( repeat, 0 );
	// var element = jQuery(this);
	while ( number_of_times > 0 ) {
		console.log('jj');
		number_of_times--;
		jQuery(this).trigger( "click" );
	}
});

jQuery('.gfield_list_group input[type="text"]').each(function() {
	var randno = Math.floor ( Math.random() * quotes.length );
	jQuery(this).val(quotes[randno] + Math.floor((Math.random() * 100000) + 1));
});
jQuery('.ginput_container_phone input[type="text"]').each(function() {

	jQuery(this).val(Math.floor((Math.random() * 100000000) + 10000000));
});
jQuery('.ginput_container_email input[type="text"]').each(function() {

	jQuery(this).val('jvn+test' + Math.floor((Math.random() * 100000) + 10000) + '@peytz.dk' );
});
jQuery('.ginput_container_date input[type="text"]').each(function() {
	var day = Math.floor((Math.random() * 30) + 1);
	var month = Math.floor((Math.random() * 12) + 1);
	var year = Math.floor((Math.random() * 3) + 2017);
	jQuery(this).val(day + '.' + month + '.' + year );
});
jQuery('.ginput_container_number input[type="text"]').each(function() {
	jQuery(this).val( Math.floor((Math.random() * 100000) + 250));
});
jQuery('.ginput_container_textarea textarea').each(function() {
	var text = "lorem ipsum doret sit amor" + Math.floor((Math.random() * 100000) + 250);
	jQuery(this).val( Math.floor((Math.random() * 100000000) + 1000));
	jQuery(this).html( Math.floor((Math.random() * 10000000) + 1000));
});
