
function searchPostCodeForAddress(address) {
	return $.ajax({
	  url: Routes.searchPostCode,
	  type: 'post',
	  dataType: 'json',
	  data: { address: address },
	  headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
}


function searchAddressForPostCode(post_code) {
	return $.ajax({
	  url: Routes.searchAddress,
	  type: 'post',
	  dataType: 'json',
	  data: { post_code: post_code },
	  headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
}
  