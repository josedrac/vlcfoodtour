if (window.taValidate == undefined) {
   window.taOnLoad   = window.onload;
   window.taValList  = Array();
   window.taValIndex = 0;
   window.taValidate = function () {
      if (window.taOnLoad != null) {
         try {
         window.taOnLoad();
         } catch (err) { }
      }
      for (ii = 0; ii < window.taValIndex; ii=ii+1) {
         fname = window.taValList[ii]
         fname();
      }
   }
   window.taAddValidator = function (fname) {
      window.taValList[window.taValIndex] = fname;
      window.taValIndex                   = window.taValIndex + 1;
      }
   window.onload = window.taValidate
}
window.taAddValidator (injectselfserveprop6363)

                  if(document.createStyleSheet) {
        document.createStyleSheet("https://static.tacdn.com/css2/widget/cdswidSSP-v21191968410b.css");
      } else {
        var stylesImport = "@import url(' https://static.tacdn.com/css2/widget/cdswidSSP-v21191968410b.css ');";
        var newSS=document.createElement('link');
        newSS.rel='stylesheet';
        newSS.href='data:text/css,'+escape(stylesImport);
        if (document.getElementsByTagName("head")[0]) {
            document.getElementsByTagName("head")[0].appendChild(newSS);
        } else {             document.write('<link rel="stylesheet" href="data:text/css,' + escape(stylesImport) + '"/>');
        }
      }

      var newJs = document.createElement('script');
    newJs.setAttribute('type', 'text/javascript');
    newJs.setAttribute('src', 'https://static.tacdn.com/js3/widget/cdswidgets_m-c-v2102084671b.js');
    document.getElementsByTagName("head")[0].appendChild(newJs);

// Returns true if we find a homepage link. Also, nofollows it if the homepage link in the embed code is nofollowed.
function checkHomePageLink(link, isHomePageLinkNoFollow) {
  var href = link.getAttribute('href');
  if (href) {
    var hrefsplit = href.split("/");
    // Check for homepage link - if http(s)://tripadvisor.com or http(s)://tripadvisor.com/
    // Now if we didn't find a nofollowed home page link in the embed code, we should not add a nofollow
    // on the home page link in the rendered code.
    if (hrefsplit.length == 3 || (hrefsplit.length == 4 && hrefsplit[3] == "")) {
      if (isHomePageLinkNoFollow) {
        link.setAttribute('rel', 'nofollow');
      }
      // Else, don't do anything. Also, we have handled the link so we don't need to check alt/anchor text for this
      return true;
    }
  }
  return false;
}

function injectselfserveprop6363() {
    var container = document.getElementById('TA_selfserveprop');
  if (container == null) {
        var scriptTags = document.getElementsByTagName("script");
    for (var i=0; i<scriptTags.length; i++)
    {
      if (scriptTags[i] != null && scriptTags[i].src != null &&
          scriptTags[i].src.indexOf("selfserveprop?uniq=") >= 0)
      {
        var msgElem = document.createElement('div');
        if (msgElem != null && msgElem != 'undefined')
        {
          msgElem.style.margin='8px';
          msgElem.style.color='red';
          msgElem.innerHTML="Please check the TripAdvisor code and install again.";
          var parentElem = scriptTags[i].parentNode;
          if (parentElem != null && parentElem != 'undefined')
          {
            parentElem.appendChild(msgElem);
          }
          break;
        }
      }
    }
    return;
  }

  // The following block of code takes care of gathering information from the embed code about whether we should follow
  // or nofollow a link in the rendered code. If the user wants to nofollow links to tripadvisor, we should respect that
  // The logic is as follows - if we find the same link in the embed and rendered code, they both should have the same
  // nofollow status. If we find a link in the rendered code which is not present in the embedded code, we should
  // nofollow it. The home page link is an exception to this rule where homepage link should always be followed unless
  // the user explicitely nofollows it.
  // Two links are same in embed and rendered code if - the anchor text matches for text link and the alt text matches
  // for image links. Two image links with no alt text are considered same as well. If we find multiple links with
  // same anchor text or same alt text in the embed code, only one of them have nofollow, adds nofollow to all the
  // corresponding links in the rendered code. For more information please check https://jira.tripadvisor.com/browse/SEO-1902

  var imageStatus = {};
  var linkStatus = {};
  // Collect all the nofollow attributes for text and image links
  var links = container.getElementsByTagName('a');
  var isHomePageLinkNoFollow = false;
  if (links) {
    for (var i = 0; i < links.length; i++) {

      hasNoFollow = links[i].getAttribute('rel') && links[i].getAttribute('rel') == 'nofollow';

      // Special case for homepage link
      var href = links[i].getAttribute('href');

      // If we didn't find a nofollowed home page link already, keep looking for one. Do this check only if the
      // link in question has nofollow set on it
      if (hasNoFollow && href && !isHomePageLinkNoFollow) {
        if (href.indexOf('tripadvisor') > -1) {
          var hrefsplit = href.split("/");
          // Check if it's actually a homepage link - if http(s)://tripadvisor.com or http(s)://tripadvisor.com/
          // and set the nofollow status of that link. Note we are interested in the case where home page link
          // is nofollowed
          if (hrefsplit.length == 3 || (hrefsplit.length == 4 && hrefsplit[3] == "")) {
            isHomePageLinkNoFollow = hasNoFollow;
            continue;
          }
        }
      }

      // Check if it's an image link. If it is, use the alt attribute as the key in the map.
      var images = links[i].getElementsByTagName("img");
      if (images && images.length > 0) {
        // Check if it's an image link
        if (images[0].getAttribute('alt')) {
          imageStatus[images[0].getAttribute('alt').trim()] = hasNoFollow;
        }
        // No alt attribute case
        else {
          // This is to handle multiple links in the embed code without alt text. In this case if at least one link has
          // nofollow, we nofollow all the no alt text links in the rendered code
          if (! imageStatus[""]) {
            imageStatus[""] = hasNoFollow;
          }
        }
      }
      // If not use the anchor text.
      else if(links[i].text) {
        var anchorText = links[i].text;
        if (!anchorText in linkStatus || !linkStatus[anchorText]) {
          linkStatus[anchorText] = hasNoFollow;
        }
      }
    }
  }


  var holderElement = document.createElement('div');
  holderElement.innerHTML = '<div id="CDSWIDSSP" class="widSSP widSSPnarrow" style="width: 240px;"> '+
'<div class="widSSPData" style="border: 1px solid #589442;"> '+
'<div class="widSSPBranding"> '+
'<dl> '+
'<dt> '+
'<a target="_blank" href="https://www.tripadvisor.com/"><img src="http://www.valenciafoodtourspain.com/wp-content/uploads/2016/08/tripadvisor-compressor.png" alt="TripAdvisor"/></a> '+
'</dt> '+
'<dt class="widSSPTagline">Know better, Book better, Go better</dt> </dl> '+
'</div><!--/ cdsBranding--> '+
'<div class="widSSPComponent"> '+
'<div class="widSSPSummary"> '+
'<dl> '+
'<a target=\'_blank\' href=\'https://www.tripadvisor.com/Attraction_Review-g187529-d10486452-Reviews-Delicious_Valencia_Food_Tours-Valencia_Province_of_Valencia_Valencian_Country.html\' onclick=\'ta.cds.handleTALink(11900,this);return true;\'> '+
'<dt class="widSSPH18">Delicious Valencia Food Tours</dt> '+
'</a> '+
'</dl> '+
'</div><!--/ cdsSummary--> '+
'</div><!--/ cdsComponent--> '+
'<div class="widSSPAll"> '+
'<ul class="widSSPReadReview"> '+
'<li><a href="https://www.tripadvisor.com/Attraction_Review-g187529-d10486452-Reviews-Delicious_Valencia_Food_Tours-Valencia_Province_of_Valencia_Valencian_Country.html" id="allreviews" onclick="ta.cds.handleTALink(11900,this);window.open(this.href, \'newTAWindow\', \'toolbar=1,resizable=1,menubar=1,location=1,status=1,scrollbars=1,width=800,height=600\'); return false">Read reviews</a></li> '+
'</ul> '+
'</div><!--/ cdsAll--> '+
'<div class="widSSPLegal">© 2016 TripAdvisor LLC</div><!--/ cdsLegal--> '+
'</div><!--/ cdsData--> '+
'</div><!--/ CDSPOP.cdsBx--> '+
'';

  var links = holderElement.getElementsByTagName('a');
  if (links) {
    for (var i = 0; i < links.length; i++) {

      // Special case for homepage link. The function returns true if we find a homepage link. In that case, we don't
      // to apply other rules.
      if(checkHomePageLink(links[i], isHomePageLinkNoFollow)) {
        continue;
      }

      // Check if it's an image link
      var images = links[i].getElementsByTagName("img");
      if (images && images.length > 0) {
        // If the image has alt attribute
        if (images[0].getAttribute('alt')) {
          var key = images[0].getAttribute('alt').trim();
          if (! (key in imageStatus) || imageStatus[key]) {
            links[i].setAttribute('rel', 'nofollow');
          }
        }
        // No alt attribute case. The no follow status for those are stored under "". If no alt attribute links in
        // embed code don't exist, we should nofollow any no alt attribute links in the rendered code. Unless it's a
        // homepage but we handled that above. I haven't seen any no alt non-homepage image link in the rendered code
        // so this is just a safeguard
        else {
          if(! ("" in imageStatus) || imageStatus[""]) {
            links[i].setAttribute('rel', 'nofollow');
          }
        }
      }
      else {
        if (links[i].text) {
          var key = links[i].text.trim();
          if (! (key in linkStatus) || linkStatus[key]) {
            links[i].setAttribute('rel', 'nofollow');
          }
        }
      }
    }

    widgetHtml = holderElement.innerHTML;
  }
  else {
    var widgetHtml = '<div id="CDSWIDSSP" class="widSSP widSSPnarrow" style="width: 240px;"> '+
'<div class="widSSPData" style="border: 1px solid #589442;"> '+
'<div class="widSSPBranding"> '+
'<dl> '+
'<dt> '+
'<a target="_blank" href="https://www.tripadvisor.com/"><img src="http://www.valenciafoodtourspain.com/wp-content/uploads/2016/08/tripadvisor-compressor.png" alt="TripAdvisor"/></a> '+
'</dt> '+
'<dt class="widSSPTagline">Know better, Book better, Go better</dt> </dl> '+
'</div><!--/ cdsBranding--> '+
'<div class="widSSPComponent"> '+
'<div class="widSSPSummary"> '+
'<dl> '+
'<a target=\'_blank\' href=\'https://www.tripadvisor.com/Attraction_Review-g187529-d10486452-Reviews-Delicious_Valencia_Food_Tours-Valencia_Province_of_Valencia_Valencian_Country.html\' onclick=\'ta.cds.handleTALink(11900,this);return true;\'> '+
'<dt class="widSSPH18">Delicious Valencia Food Tours</dt> '+
'</a> '+
'</dl> '+
'</div><!--/ cdsSummary--> '+
'</div><!--/ cdsComponent--> '+
'<div class="widSSPAll"> '+
'<ul class="widSSPReadReview"> '+
'<li><a href="https://www.tripadvisor.com/Attraction_Review-g187529-d10486452-Reviews-Delicious_Valencia_Food_Tours-Valencia_Province_of_Valencia_Valencian_Country.html" id="allreviews" onclick="ta.cds.handleTALink(11900,this);window.open(this.href, \'newTAWindow\', \'toolbar=1,resizable=1,menubar=1,location=1,status=1,scrollbars=1,width=800,height=600\'); return false">Read reviews</a></li> '+
'</ul> '+
'</div><!--/ cdsAll--> '+
'<div class="widSSPLegal">© 2016 TripAdvisor LLC</div><!--/ cdsLegal--> '+
'</div><!--/ cdsData--> '+
'</div><!--/ CDSPOP.cdsBx--> '+
'';
  }

    if (widgetHtml.indexOf("WIDGET_ERR_LINKS") != -1) {
    var linksDiv = document.createElement('div');

    var links = container.getElementsByTagName("a");
    if (links) {
      for (var i=0; i<links.length; ++i) {
                if (links[i].getElementsByTagName("img").length > 0) {
          if (widgetHtml.indexOf("WIDGET_ERR_IMAGE_LINK")) {
            widgetHtml = widgetHtml.replace('<a id="WIDGET_ERR_IMAGE_LINK" href="http://www.tripadvisor.com/" target="_blank">' ,'<a href="' + links[i] + '" target="_blank">');
            widgetHtml = widgetHtml.replace('Reviews of Hotels, Flights and Vacation Rentals', links[i].getElementsByTagName("img")[0].alt);
          }
        }
        else {
          linkDiv = document.createElement('div');
          links[i].setAttribute('target', '_blank');
          linkDiv.appendChild(links[i].cloneNode(true));
          if (linksDiv.getElementsByTagName("div").length == 0) {
            linkDiv.style.margin="8px 0 0 0";
          }
          linksDiv.appendChild(linkDiv);
        }
      }
            var cdsWidgetKey = 'selfserveprop';
      if (linksDiv.hasChildNodes() && (cdsWidgetKey != "cdshac")) {
        linksDiv.insertBefore(document.createTextNode("Read more on TripAdvisor:"), linksDiv.firstChild);       }
    }

    widgetHtml = widgetHtml.replace('<div id="WIDGET_ERR_LINKS"></div>', linksDiv.innerHTML);
  }


  container.innerHTML = widgetHtml;
}
