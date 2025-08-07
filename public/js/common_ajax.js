
function searchPostalCodeForAddress(address) {
	return $.ajax({
	  url: Routes.searcPostalCode,
	  type: 'post',
	  dataType: 'json',
	  data: { address: address },
	  headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
}


function searchAddressForPostCode(postal_code) {
	return $.ajax({
	  url: Routes.searchAddress,
	  type: 'post',
	  dataType: 'json',
	  data: { postal_code: postal_code },
	  headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
}
  