function getItemNamespace(elId) {
  return jQuery("#" + elId).attr("namespace");
}

function getItemIndex(elId) {
  return jQuery("#" + elId).attr("index");
}

function initMassActionsOnDatatables(elId) {
  $("#" + elId + " [data-check-container]").luCheckAll({
    onCheck: function (container, counter) {
      var massActions = container.find(".lu-t-c__mass-actions");
      massActions.find(".lu-value").html(counter);
      if (counter > 0) {
        massActions.addClass("is-open");
      } else {
        massActions.removeClass("is-open");
      }
    },
  });
}

function collectTableMassActionsData(elId) {
  var colectedData = {};
  $(
    "#" +
      elId +
      " [data-check-container] tbody input:checkbox.table-mass-action-check:enabled:checked"
  ).each(function (index, value) {
    colectedData[index] = $(this).val();
  });

  return colectedData;
}

function uncheckSelectAllCheck(elId) {
  closeAllSelectMassActions();
}

$(".icheck-control").on("ifClicked", function (e) {
  $(this).trigger("change", e);
  console.log("asdadasdasdas");
});
function selectRadioAction($event) {
  console.log($event);
}
function closeAllSelectMassActions() {
  $(".lu-t-c__mass-actions").removeClass("is-open");
  $(".table-mass-action-check").prop("checked", false);
  $("thead input:checkbox:enabled").prop("checked", false);
}

function initTooltipsForDatatables(elId) {
  $("#" + elId + ' [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});
}

function initModalSelects() {
  console.log("asdasdasdsad");
  jQuery(document).on("click", "#radionetworkInterface", function () {
    console.log("asdsadasd");
  });
  initContainerSelects("mgModalContainer");
}
function initContainerSelects(id) {
  $("#" + id + " select:not(.ajax)").each(function () {
    if ($(this).attr("bi-event-change")) {
      var biEventAction = $(this).attr("bi-event-change");
      $(this).selectize({
        plugins: ["remove_button"],
        onItemAdd: function (value) {
          if (biEventAction === "initReloadModal") {
            mgPageControler.modalInstance.initReloadModal();
          } else if (
            biEventAction &&
            typeof mgPageControler[biEventAction] === "function"
          ) {
            setTimeout(function () {
              mgPageControler[biEventAction]();
            }, 500);
          } else if (
            biEventAction &&
            typeof mgPageControler.vueLoader[biEventAction] === "function"
          ) {
            setTimeout(function () {
              mgPageControler.vueLoader[biEventAction]();
            }, 500);
          } else if (
            biEventAction &&
            typeof window[biEventAction] === "function"
          ) {
            setTimeout(function () {
              window[biEventAction]();
            }, 500);
          }
        },
      });
    } else {
      $(this).luSelect({});
    }
  });
}
(function($) {
    $.fn.hasScrollBar = function() {
        return this.get(0).scrollWidth > this.width();
    }
})(jQuery);
function updateValuesAfterResourceType(event) {
  jQuery("[show-on]").each(function () {
    jQuery(this).parent().hide();
  });
  if (jQuery(event.target).is(":checked")) {
    var val = jQuery(event.target).val();
    jQuery("[show-on='" + val + "']")
      .parent()
      .show();
  }
}
function initModalActions() {
  $("#mgModalContainer select").each(function () {
    if (typeof $(this).attr("reload_change") !== "undefined") {
      var fname = $(this).attr("reload_change");
      $(this).on("change", function (event) {
        if (typeof window[fname] === "function") {
          window[fname](event);
        }
      });
    }
  });
}
jQuery("[show-on]").each(function () {
  jQuery(this).parent().hide();
});
function initFormHideFieldActions() {
  console.log("asldhuhr9h923hr9sss72397r");
  $(".vue-app-main-container input").each(function () {
    if (typeof $(this).attr("hidefields") !== "undefined") {
      var fname = $(this).attr("hidefields");
      $(this).on("change", function (event) {
        if (typeof window[fname] === "function") {
          window[fname](event);
        }
      });
    }
  });
}
function initModalSwitchEvents() {
  $("#mgModalContainer :checkbox").each(function () {
    if ($(this).attr("bi-event-change")) {
      var biEventAction = $(this).attr("bi-event-change");
      $(this).change(function () {
        if (
          biEventAction &&
          typeof mgPageControler[biEventAction] === "function"
        ) {
          setTimeout(function () {
            mgPageControler[biEventAction]();
          }, 500);
        } else if (
          biEventAction &&
          typeof mgPageControler.vueLoader[biEventAction] === "function"
        ) {
          setTimeout(function () {
            mgPageControler.vueLoader[biEventAction]();
          }, 500);
          initModalSelects;
        } else if (
          biEventAction &&
          typeof window[biEventAction] === "function"
        ) {
          setTimeout(function () {
            window[biEventAction]();
          }, 500);
        }
      });
    }
  });
}

function initModalTooltips() {
  initContainerTooltips("mgModalContainer");
}

function initContainerTooltips(id) {
  $("#" + id + ' [data-toggle="lu-tooltip"], [data-tooltip]').luTooltip({});
}
function redirectTo(data) {
  if (typeof data.htmlData.redirecturl != "undefined") {
    window.location.href = data.htmlData.redirecturl;
  }
}
function mgFormControler(targetFormId) {
  this.fields = null;
  this.data = {};
  this.formId = targetFormId;

  this.loadFormFields = function () {
    var that = this;

    jQuery("#" + this.formId)
      .find("input,select,textarea")
      .each(function () {
        if (!jQuery(this).is(":disabled")) {
          var name = jQuery(this).attr("name");

          var value = null;

          if (name !== undefined) {
            var type = jQuery(this).attr("type");
            var regExp = /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]/g;
            var regExpLg =
              /([a-zA-Z_0-9]+)\[([a-zA-Z_0-9]+)\]\[([a-zA-Z_0-9]+)\]/g;

            if (type === "checkbox") {
              var value = "off";
              jQuery("#" + that.formId)
                .find('input[name="' + name + '"]')
                .each(function () {
                  if (jQuery(this).is(":checked")) {
                    value = jQuery(this).val();
                  }
                });
            } else if (type === "radio") {
              if (jQuery(this).is(":checked")) {
                var value = jQuery(this).val();
              }
            } else {
              var value = jQuery(this).val();
            }
            if (value !== null) {
              if ((result = regExpLg.exec(name))) {
                if (that.data[result[1]] === undefined) {
                  that.data[result[1]] = {};
                }
                if (that.data[result[1]][result[2]] === undefined) {
                  that.data[result[1]][result[2]] = {};
                }
                that.data[result[1]][result[2]][result[3]] = value;
              } else if ((result = regExp.exec(name))) {
                if (that.data[result[1]] === undefined) {
                  that.data[result[1]] = {};
                }
                that.data[result[1]][result[2]] = value;
              } else {
                that.data[name] = value;
              }
            }
          }
        }
      });
  };

  this.getFieldsData = function () {
    this.loadFormFields();

    return { formData: this.data };
  };

  this.updateFieldsValidationMessages = function (errorsList) {
    jQuery("#" + this.formId)
      .find("input,select,textarea")
      .each(function () {
        if (!jQuery(this).is(":disabled")) {
          var name = jQuery(this).attr("name");
          if (name !== undefined && errorsList[name] !== undefined) {
            if (
              !jQuery(this)
                .parents(".lu-form-group")
                .first()
                .hasClass("lu-is-error")
            ) {
              jQuery(this)
                .parents(".lu-form-group")
                .first()
                .addClass("lu-is-error");
            }

            var messagePlaceholder = jQuery(this)
              .parents(".lu-form-group")
              .first()
              .children(".lu-form-feedback");
            if (jQuery(messagePlaceholder).length > 0) {
              jQuery(messagePlaceholder).html(errorsList[name].slice(-1)[0]);
              if (jQuery(messagePlaceholder).attr("hidden")) {
                jQuery(messagePlaceholder).removeAttr("hidden");
              }
            }
          } else if (name !== undefined) {
            if (
              jQuery(this)
                .parents(".lu-form-group")
                .first()
                .hasClass("lu-is-error")
            ) {
              jQuery(this)
                .parents(".lu-form-group")
                .first()
                .removeClass("lu-is-error");
            }
            var messagePlaceholder = jQuery(this).next(".lu-form-feedback");
            if (jQuery(messagePlaceholder).length > 0) {
              jQuery(messagePlaceholder).html("");
              if (!jQuery(messagePlaceholder).attr("hidden")) {
                jQuery(messagePlaceholder).attr("hidden", "hidden");
              }
            }
          }
        }
      });
  };
}

//Sortable
function tldCategoriesSortableController() {
  var helperHeight = 0;

  //Add sortable for parent categories
  if (!$("#groupList.vSortable").hasClass("ui-sortable")) {
    $("#groupList.vSortable").sortable({
      items: "li:not(.lu-nav--sub li, .sortable-disabled)",
      start: function (event, ui) {
        $(ui.item).find("ul").hide();
        $("#groupList").attr("isBeingSorted", "true");
      },
      stop: function (event, ui) {
        var order = [];
        $("#groupList .nav__item").each(function (index, element) {
          if ($(element).hasClass("ui-sortable-placeholder")) {
            return;
          }

          var catId = $(element).attr("actionid");
          order.push(catId);
        });

        mgPageControler.vueLoader.updateSorting(
          order,
          "addCategoryForm",
          "ModulesGarden_AwsEc2_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm"
        );
        $(ui.item).css("height", helperHeight);
        $(ui.item).find("a").css("height", 32);
        $(ui.item).find("ul").show();
      },
      sort: function (event, ui) {
        $("#groupList").sortable("refreshPositions");
      },
      helper: function (event, li) {
        helperHeight = $(li).css("height");
        $(li).css("height", 32);
        return li;
      },
    });
  }

  //Add sortable for children - this has to be refreshed per catego content load
  $("#groupList .nav--sub").sortable({
    stop: function (event, ui) {
      var order = [];
      $(this)
        .find(".nav__item")
        .each(function (index, element) {
          if ($(element).hasClass("ui-sortable-placeholder")) {
            return;
          }

          var catId = $(element).attr("actionid");
          order.push(catId);
        });

      mgPageControler.vueLoader.updateSorting(
        order,
        "addCategoryForm",
        "ModulesGarden_AwsEc2_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AddCategoryForm"
      );
    },
  });

  //Add Sortable on table
  $("#itemContentContainer tbody.vSortable").sortable({
    stop: function (event, ui) {
      var order = [];
      $("#itemContentContainer tbody")
        .find("tr")
        .each(function (index, element) {
          if ($(element).hasClass("ui-sortable-placeholder")) {
            return;
          }

          var catId = $(element).attr("actionid");
          order.push(catId);
        });
      mgPageControler.vueLoader.updateSorting(
        order,
        "assignTldForm",
        "ModulesGarden_AwsEc2_App_UI_Widget_DoeTldConfigComponents_CategoryForms_AssignTldForm"
      );
    },
    helper: function (e, tr) {
      var $originals = tr.children();
      var $helper = tr.clone();
      $helper.children().each(function (index) {
        $(this).width($originals.eq(index).width() + 100);
      });

      return $helper;
    },
  });
}

// CUSTOM FUNCTIONS

//this is example custom action, use it for non-ajax actions
function custAction1(vueControler, params, event) {
  console.log("custAction1", vueControler, params, event);
}

//this is example custom action, use it for ajax actions
function custAction2(vueControler, params, event) {
  console.log("custAction2", vueControler, params, event);
}

function mgEmptyToPause(name, row) {
  if (!row[name] || row[name] === "") {
    return "-";
  } else {
    return row[name];
  }
}

function newCall(data) {
  console.log(data);
}

function buildOptionTag(text, value, selected, disabled) {
  var option = document.createElement("option");
  option.text = text;
  option.value = value;
  if (selected) {
    option.setAttribute("selected", "selected");
  }
  if (disabled) {
    option.setAttribute("disabled", "disabled");
  }

  return option;
}

function updateValuesAfterTypeChange() {
  let el = document.getElementsByName("type")[0];

  let possibleVals = {
    customprotocol: {
      protocol: "UDP",
      ports: "",
      disablePort: false,
      disableProtocol: false,
    },
    customtcp: {
      protocol: "TCP",
      ports: 0,
      disablePort: false,
      disableProtocol: true,
    },
    customudp: {
      protocol: "UDP",
      ports: 0,
      disablePort: false,
      disableProtocol: true,
    },
    customicmpv6: {
      protocol: "ICMP IPv6",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    customicmpv4: {
      protocol: "ICMP",
      ports: "8--1",
      disablePort: false,
      disableProtocol: true,
    },
    alltcp: {
      protocol: "TCP",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    alludp: {
      protocol: "UDP",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    allicmpv6: {
      protocol: "ICMP IPv6",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    allicmpv4: {
      protocol: "ICMP",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    alltraffic: {
      protocol: "All",
      ports: "All",
      disablePort: true,
      disableProtocol: true,
    },
    ssh: {
      protocol: "TCP",
      ports: 22,
      disablePort: true,
      disableProtocol: true,
    },
    smtp: {
      protocol: "TCP",
      ports: 25,
      disablePort: true,
      disableProtocol: true,
    },
    dnstcp: {
      protocol: "TCP",
      ports: 53,
      disablePort: true,
      disableProtocol: true,
    },
    dnsudp: {
      protocol: "UDP",
      ports: 53,
      disablePort: true,
      disableProtocol: true,
    },
    http: {
      protocol: "TCP",
      ports: 80,
      disablePort: true,
      disableProtocol: true,
    },
    pop3: {
      protocol: "TCP",
      ports: 110,
      disablePort: true,
      disableProtocol: true,
    },
    imap: {
      protocol: "TCP",
      ports: 143,
      disablePort: true,
      disableProtocol: true,
    },
    ldap: {
      protocol: "TCP",
      ports: 389,
      disablePort: true,
      disableProtocol: true,
    },
    https: {
      protocol: "TCP",
      ports: 443,
      disablePort: true,
      disableProtocol: true,
    },
    smb: {
      protocol: "TCP",
      ports: 445,
      disablePort: true,
      disableProtocol: true,
    },
    smtps: {
      protocol: "TCP",
      ports: 464,
      disablePort: true,
      disableProtocol: true,
    },
    imaps: {
      protocol: "TCP",
      ports: 993,
      disablePort: true,
      disableProtocol: true,
    },
    pop3s: {
      protocol: "TCP",
      ports: 995,
      disablePort: true,
      disableProtocol: true,
    },
    mssql: {
      protocol: "TCP",
      ports: 1433,
      disablePort: true,
      disableProtocol: true,
    },
    nfs: {
      protocol: "TCP",
      ports: 2049,
      disablePort: true,
      disableProtocol: true,
    },
    mysqlaurora: {
      protocol: "TCP",
      ports: 3306,
      disablePort: true,
      disableProtocol: true,
    },
    rdp: {
      protocol: "TCP",
      ports: 3389,
      disablePort: true,
      disableProtocol: true,
    },
    redshift: {
      protocol: "TCP",
      ports: 5439,
      disablePort: true,
      disableProtocol: true,
    },
    posgresql: {
      protocol: "TCP",
      ports: 5432,
      disablePort: true,
      disableProtocol: true,
    },
    oraclerds: {
      protocol: "TCP",
      ports: 1521,
      disablePort: true,
      disableProtocol: true,
    },
    windrmhttp: {
      protocol: "TCP",
      ports: 5985,
      disablePort: true,
      disableProtocol: true,
    },
    winrmhttps: {
      protocol: "TCP",
      ports: 5986,
      disablePort: true,
      disableProtocol: true,
    },
    elasticgraphics: {
      protocol: "TCP",
      ports: 2007,
      disablePort: true,
      disableProtocol: true,
    },
  };

  let val = el.options[el.selectedIndex].value;
  var select = $("[name=protocol]:eq(1)").selectize();
  var selectize = select[0].selectize;
  selectize.setValue(possibleVals[val].protocol);

  if (possibleVals[val].disableProtocol) {
    $(".selectize-input").eq(2).addClass("readonly locked");
  } else {
    $(".selectize-input").eq(2).removeClass("readonly locked");
  }

  $("[name=portRange]").val(possibleVals[val].ports);

  if (possibleVals[val].disablePort === true)
    $("[name=portRange]").prop("readonly", true);
  else $("[name=portRange]").removeAttr("readonly");
}

function hideAddRuleButton() {
  $("a.lu-btn.lu-btn--primary").attr("disabled", "disabled");
}

function showAddRuleButton() {
  $("a.lu-btn.lu-btn--primary").removeAttr("disabled");
}

