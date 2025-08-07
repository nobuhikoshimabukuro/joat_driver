from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time

# Chromeドライバーの自動管理でブラウザ起動
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))

try:
    # 1. 指定URLに遷移
    driver.get("https://rts.fsi-web.com/user/login")

    time.sleep(2)  # ページ読み込み待ち

    # 2. テキストボックスに入力
    company_cd = driver.find_element(By.ID, "company_cd")
    company_cd.send_keys("rtk")

    user_cd = driver.find_element(By.ID, "user_cd")
    user_cd.send_keys("9999")

    password = driver.find_element(By.ID, "password")
    password.send_keys("999999")

    # 3. ログインボタンをクリック
    login_button = driver.find_element(By.ID, "login_button")
    login_button.click()

    time.sleep(5)  # ログイン後の処理待ち

finally:
    #driver.quit()  # 必要ならブラウザを閉じる
    pass
