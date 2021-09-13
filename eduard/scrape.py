from bs4 import BeautifulSoup
import requests
import urllib

cookies = {
    'PHPSESSID': 'vuqlhui3ogrt64647cseh3l46l',
    'weather': '1',
    'redex_tyre': 'eyJzb3J0IjoicHJpb3JpdHk6ZGVzYyJ9',
    '__hs_opt_out': 'no',
    'SLG_wptGlobTipTmp': '1',
    'SLG_LNG_TRIGGER': '0',
    'SLG_G_WPT_TO': 'cs',
    'SLG_AUTO_TMP': '1',
    'SLG_GWPT_Show_Hide_tmp': '2',
    'alzura-cookie-consent': '%7B%22alzura%22%3Atrue%2C%22cloudflare%22%3Atrue%2C%22paypal%22%3Atrue%2C%22googleTagManager%22%3Atrue%2C%22googleAnalytics%22%3Atrue%2C%22facebook%22%3Atrue%2C%22googleAds%22%3Atrue%2C%22adobe%22%3Atrue%2C%22hubSpot%22%3Atrue%2C%22hotjar%22%3Atrue%7D',
    '__gads': 'ID=648b542cf59fe063:T=1620063710:S=ALNI_MYJpfj0-QqcD4hOTej-rDqnuNJkBg',
}
headers = {
    'Connection': 'keep-alive',
    'Cache-Control': 'max-age=0',
    'Upgrade-Insecure-Requests': '1',
    'Origin': 'https://tyre24.alzura.com',
    'Content-Type': 'application/x-www-form-urlencoded',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36 OPR/75.0.3969.243',
    'Accept': '*/*',
    'Sec-Fetch-Site': 'cross-site',
    'Sec-Fetch-Mode': 'cors',
    'Sec-Fetch-User': '?1',
    'Sec-Fetch-Dest': 'empty',
    'Referer': 'https://tpc.googlesyndication.com/',
    'Accept-Language': 'en-US,en;q=0.9',
    'authority': 'pagead2.googlesyndication.com',
    'user-agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36 OPR/75.0.3969.243',
    'accept': 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
    'sec-fetch-site': 'cross-site',
    'sec-fetch-mode': 'no-cors',
    'sec-fetch-dest': 'image',
    'referer': 'https://tyre24.alzura.com/',
    'accept-language': 'en-US,en;q=0.9',
    'If-None-Match': 'W/"PSA-aj-6umn3OQb9w"',
    'origin': 'https://tyre24.alzura.com',
    'X-Requested-With': 'XMLHttpRequest',
    'if-none-match': '"1616005470650935"',
    'cookie': 'RUL=EL6qyv4FGL6Rz40GIpMEATZhkON3Q2kMMz_XgScMmlayTaVghlsluKpIKClMAi-x3TQzP4i8i-Jj2fFHATeN_H51rBDb-mRbDMyY_DWAiAhLZUmZmvavdQakhnMRSvdpCGU9WEiXgvV-fbMcN0_p3WNZMh72WxPzjW0pNfk5f9GvC2g5IHe9a1YcavQrzUkojhR3fQrVKeFpiJzjjiBafSbwcUAxak6Vbi60nHImZPCzqZ2fW3wyAv5MN4fcozLEjQrEyqfjnrGPmNzC9pBmqoXhMOtcYWLVkPrI1XFRkmDEo66QfBIR0vVszQCOw7dIyCdUUpForN7RodL8hZLbzDQnFWryCFWWBgUOY3C0QZBnUdWZ-21oe0704JV450djCFWqx1lUDIG6yxrvGbKq43vLQ3ZVNPt_eQTt9rIrtOK_Q12SYzGzjV2yTf8lREg9i1TnsI_Lsl-Kn6K-XjS0i4IkxiTrXWf6NeCSdfrnfPtUXXeCShS15-NfX1oYt1t1fkM0gzN4hR8vV-RGMMnhRhSHpZDgJMedRJfUfFTe4QYMS42CjM_fvSo-g8FivvNEalVp3u5Sdhyt2dTuvOX6k8HJmlbtuvZH48pEIOdhMeoqchoerf53nHRPLTZ6uyFaQvrOOe3X-mpw21_dH0FjOxOlrcvUWmE1QT6BlGS4OUsdYm2KrBeJpCTICUWIUWahlBGh-u2eQ0Brpf1OymDtontK|cs=AP6Md-WvggSMguAJBbokpvwUrcW0; DSID=AAO-7r5kjJShklvBYpUcX2SkWncGl4bg4D9yFBds-eRTvyJaE3Td5wzU8m-rUvHiHRnH_omujVw5FMmLJlpwVzeWDhBsvBlheO1R0RBsf4t3uKukaLxdVnc; IDE=AHWqTUkucAoI71WC_4xE1XIPbJu49mkP0iVdl48iYz_e-d7VVBTrGIEXwSrmk6xe',
    'purpose': 'prefetch',
    'cache-control': 'max-age=0',
}
data = {
    'userid': '220362',
    'password': '126bd4'
}

s = requests.Session()
response = s.post('https://tyre24.alzura.com/fr/fr/user/login/page/', headers=headers, cookies=cookies, data=data)

tyre_type = '57833' #str(input('Enter Tyre24 code: ')) #e.g. 57833

html_text_be = s.get('https://tyre24.alzura.com/be/nl/item/details/id/T' + tyre_type).text
html_text_de = s.get('https://tyre24.alzura.com/de/de/item/details/id/T' + tyre_type).text
html_text_fr = s.get('https://tyre24.alzura.com/fr/fr/item/details/id/T' + tyre_type).text
html_text_at = s.get('https://tyre24.alzura.com/at/de/item/details/id/T' + tyre_type).text
html_text_pl = s.get('https://tyre24.alzura.com/pl/pl/item/details/id/T' + tyre_type).text
html_text_it = s.get('https://tyre24.alzura.com/it/it/item/details/id/T' + tyre_type).text

soup_be = BeautifulSoup(html_text_be, 'lxml')
soup_de = BeautifulSoup(html_text_de, 'lxml')
soup_fr = BeautifulSoup(html_text_fr, 'lxml')
soup_at = BeautifulSoup(html_text_at, 'lxml')
soup_pl = BeautifulSoup(html_text_pl, 'lxml')
soup_it = BeautifulSoup(html_text_it, 'lxml')

print(f'\nPrice offers for tyre T{tyre_type}')
print('|Country \t|Premium \t|Basic')

be_price_premium = soup_be.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
be_price_basic = soup_be.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
print(' BE \t\t', be_price_premium, '\t\t', be_price_basic)

de_price_premium = soup_de.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
de_price_basic = soup_de.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
print(' DE \t\t', de_price_premium, '\t\t', de_price_basic)

fr_price_premium = soup_fr.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
fr_price_basic = soup_fr.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
print(' FR \t\t', fr_price_premium, '\t\t', fr_price_basic)

at_price_premium = soup_at.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
at_price_basic = soup_at.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
print(' AT \t\t', at_price_premium, '\t\t', at_price_basic)

#pl_price_premium = soup_pl.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
#pl_price_basic = soup_pl.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
#print(' PL \t\t', pl_price_basic)

it_price_premium = soup_it.select('.table-premium-supplier td span.price-row strong span')[0].text[:-2]
it_price_basic = soup_it.select('.table-basic-supplier td span.price-row strong span')[0].text[:-2]
print(' IT \t\t', it_price_premium, '\t\t', it_price_basic)
