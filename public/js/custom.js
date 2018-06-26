
$('.js-selectize-reguler').selectize({
	sortField: 'text'
});

$('.js-selectize-multi').selectize({
	sortField: 'text',
	delimiter: ',',
	maxItems: null,
});

$('.datepicker').datepicker({
	format: 'yyyy-mm-dd', 
	autoclose: true,
});

Number.prototype.format = function(n, x, s, c) {
	var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
	num = this.toFixed(Math.max(0, ~~n));

	return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

//TAMBAH TITIK
function tandaPemisahTitik(b){
	var _minus = false;
	if (b<0) _minus = true;
	b = b.toString();
	b=b.replace(".","");
	b=b.replace("-","");
	c = "";
	panjang = b.length;
	j = 0;
	for (i = panjang; i > 0; i--){
		j = j + 1;
		if (((j % 3) == 1) && (j != 1)){
			c = b.substr(i-1,1) + "." + c;
		} else {
			c = b.substr(i-1,1) + c;
		}
	}
	if (_minus) c = "-" + c ;
	return c;
}

//HAPUS TITIK
function bersihPemisah(ini){
	a = ini.toString().replace(".","");
	//a = a.replace(".","");
	return a;
}

//Titile Case
function titleCase(str) { 
	var newstr = str.split(" "); 
	for(i=0;i<newstr.length;i++){ 
		if(newstr[i] == "") continue; 
		var copy = newstr[i].substring(1).toLowerCase(); 
		newstr[i] = newstr[i][0].toUpperCase() + copy; 
	} 
	newstr = newstr.join(" "); 
	return newstr; 
}  