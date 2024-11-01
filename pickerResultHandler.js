/*******************************************************************************

 * Copyright IBM Corp. 2017
 *

 * Licensed under the Apache License, Version 2.0 (the "License");

 * you may not use this file except in compliance with the License.

 * You may obtain a copy of the License at

 *

 * http://www.apache.org/licenses/LICENSE-2.0

 *

 * Unless required by applicable law or agreed to in writing, software

 * distributed under the License is distributed on an "AS IS" BASIS,

 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

 * See the License for the specific language governing permissions and

 * limitations under the License.

 *******************************************************************************/
 function getRteNode() {
	var result = null;

	var iframeDocument = null;
	var iframes = document.querySelectorAll('#content_ifr');
	for (var i=iframes.length-1; i>=0; i--) {
	  //console.log(iframes[i]);
	  iframeDocument = iframes[i].contentDocument || iframes[i].contentWindow.document;
	}


	//if (iframeDocument) {
	//  console.log(iframeDocument);
	//}


	if (iframeDocument) {
		var elements = iframeDocument.querySelectorAll('[contenteditable=true]');//(requires FF 3.1+, Safari 3.1+, IE8+)
		for (var i=elements.length-1; i>=0; i--) {
		//  console.log(elements[i]);
		  result = elements[i];
		}
	}

	if (result) {
	  console.log(result);
	} else {
		console.error("no rte element found.");
	}

	return result;
}


function assembleResourcURL(resultJSON) {
	console.log("generate url");
        // this assembles the url to the resource from akamai
        return "https://"+hostRendering+"/"+tenantId+resultJSON.path;
}




    

