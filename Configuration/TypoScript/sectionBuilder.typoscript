plugin.tx_tgmreveal_reveal {
	lib.pages = COA
	lib.pages {
		10 = HMENU
		10 {
			special = directory
			special.value.data = page:uid
			# Includes hidden pages
			includeNotInMenu = 1
			# "special.value.data" defines the 'new' root-page. "entryLevel = 0" starts at this page
			entryLevel = 0

			1 = TMENU
			1 {
				# Expands all sub pages of the current menu layer
				expAll = 1
				NO = 1
				NO {
					# Removes link
					doNotLinkIt = 1

					stdWrap.cObject = CONTENT
					stdWrap.cObject {
						table = tt_content
						select {
							pidInList.field = uid
							andWhere = colPos=0
						}
					}

					wrapItemAndSub >
					# Fetches content elements from a site
					wrapItemAndSub {
						cObject = COA
						cObject {
							1 = TEXT
							1.value = <section

							# Required for Fancybox groups
							2 = TEXT
							2.insertData = 1
							2.noTrimWrap = | ||
							2.value = class="ce-pid_{field:pid}" data-ce_pid="{field:pid}"

							5 = CASE
							5 {
								key.field = tx_tgm_reveal_bg_type

								color < lib.tgm_reveal.backgroundColor
								iframe < lib.tgm_reveal.backgroundIFrame

								image = CASE
								image {
									key.field = tx_tgm_reveal_bg_image_selectBy

									fal = COA
									fal.5 < lib.tgm_reveal.backgroundImage.falUrl
									fal.10 < lib.tgm_reveal.backgroundImage.size
									fal.15 < lib.tgm_reveal.backgroundImage.position
									fal.20 < lib.tgm_reveal.backgroundImage.repeat

									link = COA
									link.5 < lib.tgm_reveal.backgroundImage.directLink
									link.10 < lib.tgm_reveal.backgroundImage.size
									link.15 < lib.tgm_reveal.backgroundImage.position
									link.20 < lib.tgm_reveal.backgroundImage.repeat
								}

								video = CASE
								video {
									key.field = tx_tgm_reveal_bg_video_selectBy

									fal = COA
									fal.5 < lib.tgm_reveal.backgroundVideo.falUrl
									fal.10 < lib.tgm_reveal.backgroundVideo.loop
									fal.15 < lib.tgm_reveal.backgroundVideo.muted

									link = COA
									link.5 < lib.tgm_reveal.backgroundVideo.directLink
									link.10 < lib.tgm_reveal.backgroundVideo.loop
									link.15 < lib.tgm_reveal.backgroundVideo.muted
								}
							}

							10 < lib.tgm_reveal.attribute.transition
							15 < lib.tgm_reveal.attribute.state
							20 < lib.tgm_reveal.attribute.markdown
							25 < lib.tgm_reveal.attribute.trim
							50 < lib.tgm_reveal.attribute.additional

							85 = TEXT
							85.value = >|

							90 < lib.tgm_reveal.speakerNotes

							95 = TEXT
							95.value = </section>
						}
					}
				}
			}

			2 < .1
		}
	}
}