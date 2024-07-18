package com.example.foodtruckmapfinder;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class MenuItem {

    @SerializedName("menu_id")
    @Expose
    private String menuId;
    @SerializedName("truck_id")
    @Expose
    private String truckId;
    @SerializedName("menu_name")
    @Expose
    private String menuName;
    @SerializedName("menu_desc")
    @Expose
    private String menuDesc;
    @SerializedName("menu_price")
    @Expose
    private String menuPrice;
    @SerializedName("menu_image")
    @Expose
    private String menuImage;

    public String getMenuId() {
        return menuId;
    }

    public void setMenuId(String menuId) {
        this.menuId = menuId;
    }

    public String getTruckId() {
        return truckId;
    }

    public void setTruckId(String truckId) {
        this.truckId = truckId;
    }

    public String getMenuName() {
        return menuName;
    }

    public void setMenuName(String menuName) {
        this.menuName = menuName;
    }

    public String getMenuDesc() {
        return menuDesc;
    }

    public void setMenuDesc(String menuDesc) {
        this.menuDesc = menuDesc;
    }

    public String getMenuPrice() {
        return menuPrice;
    }

    public void setMenuPrice(String menuPrice) {
        this.menuPrice = menuPrice;
    }

    public String getMenuImage() {
        return menuImage;
    }

    public void setMenuImage(String menuImage) {
        this.menuImage = menuImage;
    }
}
