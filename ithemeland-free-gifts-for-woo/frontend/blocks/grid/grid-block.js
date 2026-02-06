var reactElement = window.wp.element,
  blocks = wp.blocks;
blocks.registerBlockType("wgb/gift-grid", {
  title: "Gift Grid",
  icon: "grid-view",
  category: "woocommerce",
  attributes: {
    number_per_page: {
      type: "number",
      default: 6,
    },
    desktop_columns: {
      type: "string",
      default: "wgb-col-md-2",
    },
    tablet_columns: {
      type: "string",
      default: "wgb-col-sm-2",
    },
    mobile_columns: {
      type: "string",
      default: "wgb-col-2",
    },
  },
  edit: function (props) {
    return reactElement.createElement(
      "div",
      {
        class: "itg_shortcode_gift_products_wrapper",
      },
      reactElement.createElement(
        "div",
        {
          class: "adv-gift-section wgb-frontend-gifts",
        },
        reactElement.createElement(
          "div",
          {
            class: "wgb-header-cnt",
          },
          reactElement.createElement(
            "h2",
            {
              class: "wgb-title text-capitalize font-weight-bold",
            },
            "Our Gift"
          )
        ),
        reactElement.createElement(
          "div",
          {
            class: "wgb-grid-cnt",
          },
          reactElement.createElement(
            "div",
            {
              class: "page_1 pw_gift_pagination_div  pw-gift-active  wgb-item-layout2",
            },
            reactElement.createElement(
              "div",
              {
                class: "wgb-row",
              },
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 1",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 1"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "45,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 2",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 2"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "3,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 2",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 3"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "3,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 2",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 4"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "3,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 2",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 5"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "3,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-col-md-2 wgb-col-sm-2 wgb-col-2 2",
                },
                reactElement.createElement(
                  "div",
                  {
                    class: "wgb-product-item-cnt",
                  },
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-thumb",
                    },
                    reactElement.createElement("img", {
                      width: "300",
                      height: "300",
                      src: WGBL_GRID_DATA.images.wc_placeholder,
                      class: "attachment-woocommerce_thumbnail size-woocommerce_thumbnail",
                      alt: "",
                    }),
                    reactElement.createElement(
                      "div",
                      {
                        class: "wgb-item-overlay",
                      },
                      "Available Gift : 1"
                    )
                  ),
                  reactElement.createElement(
                    "div",
                    {
                      class: "wgb-item-content",
                    },
                    reactElement.createElement(
                      "h2",
                      {
                        class: "wgb-item-title font-weight-bold",
                      },
                      "Gift 6"
                    ),
                    reactElement.createElement(
                      "div",
                      {
                        class: "gift-price",
                      },
                      reactElement.createElement(
                        "div",
                        {
                          class: "gift-price",
                        },
                        reactElement.createElement(
                          "del",
                          null,
                          reactElement.createElement(
                            "span",
                            {
                              class: "woocommerce-Price-amount amount",
                            },
                            reactElement.createElement(
                              "span",
                              {
                                class: "woocommerce-Price-currencySymbol",
                              },
                              "$"
                            ),
                            "3,00"
                          )
                        ),
                        " ",
                        reactElement.createElement("ins", null, "Free")
                      )
                    ),
                    reactElement.createElement(
                      "a",
                      {
                        class: "wgb-add-gift-btn btn-click-add-gift-button",
                        href: "#",
                      },
                      "Add Gift"
                    )
                  )
                )
              )
            )
          ),
          reactElement.createElement(
            "div",
            {
              class: "wgb-pagination-cnt",
            },
            reactElement.createElement(
              "div",
              {
                class: "wgb-paging-item",
              },
              reactElement.createElement(
                "span",
                null,
                "Page ",
                reactElement.createElement(
                  "strong",
                  {
                    id: "wgb-cart-pagination-current-page",
                  },
                  "1"
                ),
                "of 2"
              ),
              reactElement.createElement(
                "div",
                {
                  class: "wgb-pages",
                },
                reactElement.createElement(
                  "a",
                  {
                    href: "javascript:;",
                    class: "pw_gift_pagination_num wgb-active-page",
                  },
                  "1"
                ),
                reactElement.createElement(
                  "a",
                  {
                    href: "javascript:;",
                    class: "pw_gift_pagination_num",
                  },
                  "2"
                )
              )
            )
          )
        )
      )
    );
  },
});
