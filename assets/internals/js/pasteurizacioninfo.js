(function () {
	$("#verFrascos").on("click", function() {
		if ( $("#example1").find("input[type=checkbox]:checked").length === 3) {
			$("#agregarFrascos").submit();
		} else {
			alert("son 35 pe");
		}
	})
	$("#botonConfirmaFrascos").on("click", function() {
		console.log($("#confirmaFrascos"));
		$("#confirmaFrascos").submit();
	})
	$("#botonRegPasteu").on("click", function() {
		console.log($("#regPasteurizacion"));
		$("#regPasteurizacion").submit();
	})

})()