document.addEventListener("DOMContentLoaded", function () {
  const tocContainers = document.querySelectorAll(".toc-questions");
  if (!tocContainers.length) return;

  tocContainers.forEach((tocContainer, containerIndex) => {
    const tocHeading = tocContainer.previousElementSibling?.classList.contains(
      "toc-heading",
    )
      ? tocContainer.previousElementSibling
      : null;

    const selectedHeadings = tocContainer.dataset.tocHeadings?.split(",") || [
      "h2",
      "h3",
    ];
    const excludedClasses =
      tocContainer.dataset.tocExclude?.split(",").filter(Boolean) || [];

    const headingSelector = selectedHeadings
      .map((tag) => `.site-main ${tag}`)
      .join(", ");

    let headings = Array.from(document.querySelectorAll(headingSelector));

    if (excludedClasses.length) {
      headings = headings.filter(
        (heading) => !excludedClasses.some((cls) => heading.closest(`.${cls}`)),
      );
    }

    if (!headings.length) {
      if (tocHeading) tocHeading.style.display = "none";
      return;
    }

    const parentAttr = "toc-parent-" + containerIndex;
    tocContainer.setAttribute("data-toc-parent", parentAttr);

    const addedIds = new Set();
    const addedTitles = new Set();

    let currentSection = null;
    let childItems = [];

    const accordionWrapper = document.createElement("div");
    accordionWrapper.className = "accordion-wrapper";
    tocContainer.appendChild(accordionWrapper);

    const isParentHeading = (tag) => tag === selectedHeadings[0].toUpperCase();

    function addItem(section) {
      if (!section || addedTitles.has(section.title)) return;
      addedTitles.add(section.title);

      const hasChildren = section.items?.length;
      const randomID =
        "accordion-" +
        containerIndex +
        "-" +
        Math.random().toString(36).slice(2, 9);

      accordionWrapper.insertAdjacentHTML(
        "beforeend",
        `
<div class="accordion-item ${hasChildren ? "hasContent" : "noContent"}">
<span class="accordion-header">
${
  hasChildren
    ? `<button class="accordion-button collapsed"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#${randomID}">
        ${section.title}
      </button>`
    : `<button class="accordion-button single-section no-arrow"
        type="button"
        data-scroll-target="#${section.id}">
        ${section.title}
      </button>`
}
</span>
${
  hasChildren
    ? `
<div id="${randomID}"
class="accordion-collapse collapse"
data-bs-parent="[data-toc-parent='${parentAttr}']">
<div class="accordion-body">
<ul>
${section.items
  .map(
    (item) =>
      `<li><a href="#${item.id}" class="toc-link">${item.title}</a></li>`,
  )
  .join("")}
</ul>
</div>
</div>`
    : ""
}
</div>`,
      );
    }

    headings.forEach((heading, index) => {
      if (heading.closest(".question-accordion")) return;

      const title = heading.innerText.trim();
      if (!title) return;

      if (!heading.id) heading.id = "toc-section-" + index;
      if (addedIds.has(heading.id)) return;
      addedIds.add(heading.id);

      if (isParentHeading(heading.tagName)) {
        if (currentSection) {
          currentSection.items = childItems;
          addItem(currentSection);
        }

        currentSection = { title, id: heading.id, items: [] };
        childItems = [];
      } else {
        if (currentSection) {
          childItems.push({ title, id: heading.id });
        } else {
          addItem({ title, id: heading.id, items: [] });
        }
      }
    });

    if (currentSection) {
      currentSection.items = childItems;
      addItem(currentSection);
    }

    // 🔹 Hide ACF main heading if no accordion items were added
    if (!accordionWrapper.children.length && tocHeading) {
      tocHeading.style.display = "none";
    }
  });

  function scrollToOffset(element, offset = 120) {
    const pos = element.getBoundingClientRect().top + window.pageYOffset;
    window.scrollTo({ top: pos - offset, behavior: "smooth" });
  }

  document.addEventListener("click", function (e) {
    const link = e.target.closest(".toc-link");
    const single = e.target.closest(".single-section");

    if (link) {
      e.preventDefault();
      const el = document.getElementById(
        link.getAttribute("href").substring(1),
      );
      if (el) scrollToOffset(el);
    }

    if (single) {
      const el = document.getElementById(
        single.dataset.scrollTarget.substring(1),
      );
      if (el) scrollToOffset(el);
    }
  });
});

// Menu Start

document.addEventListener("DOMContentLoaded", function () {
  /** JS to add a div for table responsive */
  const tables = document.querySelectorAll("table");
  tables.forEach(function (table) {
    const wrapper = document.createElement("div");
    wrapper.className = "table-responsive";

    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);
  });

  /** JS for accrodion */
  jQuery(".schema-faq").each(function () {
    const $faqWrapper = jQuery(this);
    const $faqsections = $faqWrapper.find(".schema-faq-section");

    $faqsections.find(".schema-faq-answer").hide();

    $faqsections.first().addClass("active").find(".schema-faq-answer").show();

    $faqWrapper.find(".schema-faq-question").on("click", function () {
      const $current = jQuery(this).closest(".schema-faq-section");
      const isOpen = $current.hasClass("active");

      $faqsections
        .removeClass("active")
        .find(".schema-faq-answer")
        .slideUp(300);

      if (!isOpen) {
        $current.addClass("active").find(".schema-faq-answer").slideDown(300);
      }
    });
  });
  /** JS for accrodion */
  const hamburger = document.querySelector(".menu-toggle");
  const menu = document.querySelector(".games-main-menu");
  const overlay = document.createElement("div");

  overlay.classList.add("overlay");
  document.body.appendChild(overlay);

  /* Toggle mobile menu */
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("active");
    menu.classList.toggle("active");
    overlay.classList.toggle("active");
    document.querySelector(".main-navigation").classList.toggle("active");
    document.body.classList.toggle("menu-active");
  });

  /* Close when clicking outside */
  overlay.addEventListener("click", function () {
    hamburger.classList.remove("active");
    menu.classList.remove("active");
    overlay.classList.remove("active");
    document.body.classList.remove("menu-active");
    document.querySelector(".sub-nav-menu").classList.remove("active");
    document.querySelector(".main-navigation").classList.remove("active");
  });

  /* Accordion submenu (mobile only) */
  // const parents = document.querySelectorAll(".menu-item-has-children > a");

  // parents.forEach((anchor) => {
  //   anchor.addEventListener("click", function (e) {
  //     if (window.innerWidth <= 1024) {
  //       e.preventDefault();

  //       const parentLi = this.parentElement;

  //       document.querySelectorAll(".menu-item-has-children").forEach((item) => {
  //         if (item !== parentLi) {
  //           item.classList.remove("menu-open");
  //         }
  //       });

  //       parentLi.classList.toggle("menu-open");
  //     }
  //   });
  // });
});

// Menu End

/** Search ajax */
jQuery(document).ready(function ($) {
  const headerSearchWrapper = $(".header-live-search");
  if (!headerSearchWrapper.length) return;

  const searchInput = headerSearchWrapper.find(".search-field");
  const searchResult = headerSearchWrapper.find(".live-search-results");

  function resetLiveSearch() {
    searchInput.val("");
    searchResult.hide().html("");
  }

  /* Open Search Popup */
  $(".search-open-btn").on("click", function (e) {
    e.preventDefault();
    resetLiveSearch();
    $(".search-result-form-wrapper").addClass("active");
    searchInput.trigger("focus");
  });

  jQuery(".search-field").on("keyup", function () {
    var live_search_el = $(this)
      .parents(".live-search-wrapper")
      .find(".live-search-results");
    let keyword = $(this).val();

    if (keyword.length < 2) {
      live_search_el.hide();
      return;
    }

    $.ajax({
      url: ga_ajax.ajax_url,
      type: "POST",
      data: {
        action: "ga_live_search",
        keyword: keyword,
        nonce: ga_ajax.nonce,
      },
      success: function (response) {
        if (response.trim() !== "") {
          live_search_el.html(response).show();
        } else {
          live_search_el.hide();
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX error:", error);
      },
    });
  });
  $(document).on("click", function (e) {
    if (
      !$(e.target).closest(".search-result-form-wrapper, .search-open-btn")
        .length &&
      $(".search-result-form-wrapper").hasClass("active")
    ) {
      resetLiveSearch();
      $(".search-result-form-wrapper").removeClass("active");
    }
  });
  $(".search-field").on("keyup input", function () {
    var live_search_el = $(this)
      .parents(".live-search-wrapper")
      .find(".live-search-results");
    let keyword = $(this).val();

    if (keyword.length < 1) {
      live_search_el.hide().empty();
    }
  });
});
// Close search on button click
jQuery(document).on("click", ".search-close-btn", function () {
  var wrapper = jQuery(this).closest(".search-result-form-wrapper");

  // Reset input
  wrapper.find(".search-field").val("");

  // Clear and hide results
  wrapper.find(".live-search-results").hide().empty();

  // Remove active class
  wrapper.removeClass("active");
});

/** author listing load more */
jQuery(function ($) {
  const button = $("#load-more-authors");
  let page = parseInt(button.data("page")) || 1;

  button.on("click", function () {
    alert("click");
    $.ajax({
      url: ga_ajax.ajax_url,
      type: "POST",
      data: {
        action: "ga_load_more_authors",
        page: page,
      },
      beforeSend: function () {
        button.text("Loading...");
        button.prop("disabled", true);
      },
      success: function (response) {
        if (response.success && response.data.html) {
          $(".team-grid").append(response.data.html); // Fixed selector
          page++;

          if (response.data.last_page) {
            button.remove();
          } else {
            button.text("Load More");
            button.prop("disabled", false);
          }
        } else {
          button.remove();
        }
      },
      error: function () {
        button.text("Load More");
        button.prop("disabled", false);
      },
    });
  });

  // Optional: hide button if less than first page of authors
  if ($(".team-grid .team-card").length < 36) {
    button.hide();
  }
});

/**
 * Truncates an HTML string to `maxChars` visible characters,
 * preserving all tags, attributes, bold, links, etc.
 */
function truncateHTMLToChars(html, maxChars) {
  var temp = document.createElement("div");
  temp.innerHTML = html;
  var charCount = 0;

  function processNode(node) {
    if (node.nodeType === 3) {
      var remaining = maxChars - charCount;
      if (node.nodeValue.length > remaining) {
        node.nodeValue = node.nodeValue.substring(0, remaining);
        charCount = maxChars;
      } else {
        charCount += node.nodeValue.length;
      }
    } else if (node.nodeType === 1) {
      var children = Array.from(node.childNodes);
      for (var i = 0; i < children.length; i++) {
        if (charCount >= maxChars) {
          for (var j = i; j < children.length; j++) {
            if (children[j].parentNode) {
              node.removeChild(children[j]);
            }
          }
          break;
        }
        processNode(children[i]);
      }
    }
  }

  var topChildren = Array.from(temp.childNodes);
  for (var i = 0; i < topChildren.length; i++) {
    if (charCount >= maxChars) {
      for (var j = i; j < topChildren.length; j++) {
        if (topChildren[j].parentNode) {
          temp.removeChild(topChildren[j]);
        }
      }
      break;
    }
    processNode(topChildren[i]);
  }

  return temp.innerHTML;
}

/**
 * Trims the last text node in an HTML string to the last word boundary,
 * preserving all tags.
 */
function trimHTMLToLastWord(html) {
  var temp = document.createElement("div");
  temp.innerHTML = html;

  function findLastTextNode(node) {
    var children = node.childNodes;
    for (var i = children.length - 1; i >= 0; i--) {
      var child = children[i];
      if (child.nodeType === 3 && child.nodeValue.trim().length > 0) {
        return child;
      } else if (child.nodeType === 1) {
        var found = findLastTextNode(child);
        if (found) return found;
      }
    }
    return null;
  }

  var lastText = findLastTextNode(temp);
  if (lastText) {
    var text = lastText.nodeValue.trimEnd();
    var lastSpace = text.lastIndexOf(" ");
    if (lastSpace > 0) {
      lastText.nodeValue = text.substring(0, lastSpace);
    }
  }
  return temp.innerHTML;
}

/**
 * Injects the Show More link inside the last <p> tag of the HTML.
 * If no <p> exists, wraps everything in one.
 */
function appendShowMoreToLastP(html, showMoreLink) {
  var temp = document.createElement("div");
  temp.innerHTML = html;
  var paragraphs = temp.querySelectorAll("p");
  if (paragraphs.length > 0) {
    var lastP = paragraphs[paragraphs.length - 1];
    lastP.innerHTML = lastP.innerHTML + showMoreLink;
  } else {
    temp.innerHTML = "<p>" + html + showMoreLink + "</p>";
  }
  return temp.innerHTML;
}

/**
 * Binary searches for the maximum visible characters that fit within
 * maxHeight, then sets the truncated HTML with Show More inside the last <p>.
 */
function truncateText($element, remainingHTML, maxHeight) {
  var showMoreLink =
    '... <a href="#" class="excerpt-show-more-inline">Show More</a>';

  var totalLength = jQuery("<div>").html(remainingHTML).text().length;

  var low = 0;
  var high = totalLength;
  var bestFit = 0;

  while (low <= high) {
    var mid = Math.floor((low + high) / 2);
    var truncatedHTML = truncateHTMLToChars(remainingHTML, mid);
    $element.html(appendShowMoreToLastP(truncatedHTML, showMoreLink));

    if ($element[0].scrollHeight <= maxHeight) {
      bestFit = mid;
      low = mid + 1;
    } else {
      high = mid - 1;
    }
  }

  var result = trimHTMLToLastWord(truncateHTMLToChars(remainingHTML, bestFit));

  $element.html(appendShowMoreToLastP(result, showMoreLink));
  var safety = 0;
  while ($element[0].scrollHeight > maxHeight && safety < 20) {
    var prev = result;
    result = trimHTMLToLastWord(result);
    $element.html(appendShowMoreToLastP(result, showMoreLink));
    if (result === prev) break;
    safety++;
  }

  $element.data("truncated-html", result);
}

/**
 * Processes all excerpt wrappers on the page:
 * measures full height vs 3-line max and truncates if needed.
 */
function processExcerpts() {
  jQuery(".excerpt-toggle-wrapper").each(function () {
    var $wrapper = jQuery(this);
    var $excerpt = $wrapper.find(".post-excerpt");

    var isExpanded = $excerpt.data("is-expanded") === true;
    if (isExpanded) return;

    var fullHTML = $excerpt.html().trim();
    if (!$excerpt.data("full-html")) {
      $excerpt.data("full-html", fullHTML);
    }

    var remainingHTML = $excerpt.data("full-html");

    var lineHeight = parseFloat($excerpt.css("line-height"));
    var fontSize = parseFloat($excerpt.css("font-size"));
    if (isNaN(lineHeight)) lineHeight = fontSize * 1.6;

    var MIN_LINES = 3;
    var maxLineHeight = lineHeight * MIN_LINES;

    // Measure full height using the real HTML
    $excerpt.html(remainingHTML);
    var fullHeight = $excerpt[0].scrollHeight;

    if (fullHeight > maxLineHeight) {
      truncateText($excerpt, remainingHTML, maxLineHeight);
    }
    // else: content fits within 3 lines, leave it as-is
  });
}

processExcerpts();

// Show More: expand to full HTML, append Show Less inside last <p>
jQuery(document).on("click", ".excerpt-show-more-inline", function (e) {
  e.preventDefault();
  var $element = jQuery(this).closest(".post-excerpt");
  var fullHTML = $element.data("full-html");

  $element.html(fullHTML);

  var showLessLink =
    ' <a href="#" class="excerpt-show-less-inline">Show Less</a>';
  var $lastP = $element.find("p").last();
  if ($lastP.length > 0) {
    $lastP.append(showLessLink);
  } else {
    $element.append("<p>" + showLessLink + "</p>");
  }

  $element.data("is-expanded", true);
});

// Show Less: collapse back to truncated HTML with Show More inside last <p>
jQuery(document).on("click", ".excerpt-show-less-inline", function (e) {
  e.preventDefault();
  var $element = jQuery(this).closest(".post-excerpt");

  var truncatedHTML = $element.data("truncated-html");
  var showMoreLink =
    '... <a href="#" class="excerpt-show-more-inline">Show More</a>';

  $element.html(appendShowMoreToLastP(truncatedHTML, showMoreLink));
  $element.data("is-expanded", false);
});

// Re-process on window resize (debounced)
var resizeTimer;
jQuery(window).on("resize", function () {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function () {
    jQuery(".post-excerpt").each(function () {
      jQuery(this).data("is-expanded", false);
    });
    processExcerpts();
  }, 250);
});
// Search Slider

const btn = document.getElementById("toggleBtn");
const box = document.getElementById("slideBox");

btn.addEventListener("click", function () {
  box.classList.toggle("active");
});

//  JS for Desktop submenu - pt
/* 
const container = document.querySelector(".container");

function handleMegaMenu() {
  if (window.innerWidth < 1025) return;

  document.querySelectorAll(".menu-item-has-children").forEach(function (item) {
    item.addEventListener("mouseenter", function () {
      const submenu = this.querySelector(".sub-nav-menu");
      if (!submenu) return;

      const containerRect = container.getBoundingClientRect();
      const itemRect = this.getBoundingClientRect();

      // Reset
      submenu.style.left = "0px";
      submenu.style.right = "auto";
      submenu.style.width = "";
      if (item.classList.contains("menu-layout-full-width")) {

        submenu.style.width = containerRect.width + "px";

        const leftPosition = containerRect.left - itemRect.left;
        submenu.style.left = leftPosition + "px";

        return;
      }
      const submenuRect = submenu.getBoundingClientRect();

      if (submenuRect.right > containerRect.right) {
        const shiftLeft = submenuRect.right - containerRect.right;
        submenu.style.left = -shiftLeft + "px";
      }

      if (submenuRect.left < containerRect.left) {
        const shiftRight = containerRect.left - submenuRect.left;
        submenu.style.left = shiftRight + "px";
      }
    });
  });
}

handleMegaMenu();

window.addEventListener("resize", handleMegaMenu);
*/
// JS for Desktop Submenu End

function handleMegaMenu() {
  if (window.innerWidth < 1025) return;

  const container = document.querySelector(".container");
  const containerRect = container.getBoundingClientRect();

  document.querySelectorAll(".menu-item-has-children").forEach(function (item) {
    item.addEventListener("mouseenter", function () {
      const submenu = this.querySelector(
        ":scope > .sub-nav-menu, :scope > .sub-nav-menu",
      );
      if (!submenu) return;

      // RESET
      submenu.style.left = "";
      submenu.style.right = "";
      this.classList.remove("submenu-open-left", "submenu-open-right");

      const itemRect = this.getBoundingClientRect();

      /* ================= FULL WIDTH ================= */

      if (this.classList.contains("menu-layout-full-width")) {
        submenu.style.width = containerRect.width + "px";
        submenu.style.left = containerRect.left - itemRect.left + "px";
        return;
      }

      /* ================= AUTO LEFT / RIGHT ================= */

      // If item center is on right half → open left
      const itemCenter = itemRect.left + itemRect.width / 2;

      if (itemCenter > containerRect.left + containerRect.width / 2) {
        submenu.style.right = "0";
        submenu.style.left = "auto";
        this.classList.add("submenu-open-left");
      } else {
        submenu.style.left = "0";
        submenu.style.right = "auto";
        this.classList.add("submenu-open-right");
      }

      // Show temporarily for measurement

      requestAnimationFrame(() => {
        const rect = submenu.getBoundingClientRect();

        /* ===== Prevent RIGHT overflow ===== */
        if (rect.right > containerRect.right) {
          submenu.style.left = "auto";
          submenu.style.right = "0";
          this.classList.remove("submenu-open-right");
          this.classList.add("submenu-open-left");
        }

        /* ===== Prevent LEFT overflow ===== */
        if (rect.left < containerRect.left) {
          submenu.style.left = containerRect.left - rect.left + "px";
        }

        /* ================= HANDLE NESTED SUBMENUS ================= */

        const nestedMenus = submenu.querySelectorAll(
          ".sub-menu .sub-nav-menu ",
        );

        nestedMenus.forEach((nested) => {
          const nestedRect = nested.getBoundingClientRect();

          if (nestedRect.right > containerRect.right) {
            nested.style.left = "auto";
            nested.style.right = "100%";
          }

          if (nestedRect.left < containerRect.left) {
            nested.style.left = "100%";
            nested.style.right = "auto";
          }
        });
      });
    });
  });
}

handleMegaMenu();
window.addEventListener("resize", handleMegaMenu);
//  JS for Desktop submenu - pt

document.addEventListener("DOMContentLoaded", function () {
  const menuParents = document.querySelectorAll(".menu-item-has-children");

  menuParents.forEach(function (item) {
    const link = item.querySelector(":scope > a");
    if (!link) return;

    // Prevent duplicate arrow
    if (link.querySelector(".menu-arrow")) return;

    const arrow = document.createElement("span");
    arrow.className = "menu-arrow";

    link.appendChild(arrow);
  });
});

//JS for Responsive Menu
if (window.matchMedia("(max-width: 1024px)").matches) {
  document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const menuOverlay = document.getElementById("menuOverlay");
    const navigation = document.getElementById("site-navigation");
    // const navigation = document.querySelector(".main-navigation");
    const menuLinks = document.querySelectorAll(
      ".menu-item-has-children > a .menu-arrow",
    );
    const backButtons = document.querySelectorAll(".mobile-back-button");

    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      navigation.classList.toggle("active");
      menuOverlay.classList.toggle("active");
      document.querySelectorAll(".sub-nav-menu").forEach((sub) => {
        sub.classList.remove("active");
      });
    });

    /* OPEN / CLOSE MAIN MENU */
    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      navigation.classList.toggle("active");
      menuOverlay.classList.toggle("active");
    });

    menuOverlay.addEventListener("click", closeMenu);

    function closeMenu() {
      menuToggle.classList.remove("active");
      navigation.classList.remove("active");
      menuOverlay.classList.remove("active");
    }

    /* OPEN SUBMENU */
    menuLinks.forEach((arrow) => {
      arrow.addEventListener("click", function (e) {
        e.preventDefault(); // prevent link redirect
        e.stopPropagation(); // stop bubbling to <a>

        const parentLink = this.closest("a");
        const subMenu = parentLink.nextElementSibling;

        if (subMenu) {
          subMenu.classList.add("active");
        }
      });
    });

    /* BACK BUTTON */
    backButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        this.closest(".sub-nav-menu").classList.remove("active");
      });
    });
  });
}
//Js for responsive menu

// Back to Top Start
const scrollBtn = document.getElementById("scrollTopBtn");

// Show button after 500px scroll
window.addEventListener("scroll", () => {
  if (window.scrollY > 500) {
    scrollBtn.style.display = "block";
  } else {
    scrollBtn.style.display = "none";
  }
});

// Scroll to top smoothly
scrollBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
// Back to Top End
