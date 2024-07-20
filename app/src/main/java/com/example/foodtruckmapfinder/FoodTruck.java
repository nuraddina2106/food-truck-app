package com.example.foodtruckmapfinder;
import com.google.gson.annotations.SerializedName;

public class FoodTruck {

    @SerializedName("truck_id")
    private String truckId;

    @SerializedName("name")
    private String name;

    @SerializedName("operator_name")
    private String operatorName;

    @SerializedName("menu_name")
    private String[] menuNames;

    @SerializedName("business_hours")
    private String businessHours;

    @SerializedName("latitude")
    private String latitude;

    @SerializedName("longitude")
    private String longitude;

    // Getters and setters
    public String getTruckId() {
        return truckId;
    }

    public void setTruckId(String truckId) {
        this.truckId = truckId;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getOperatorName() {
        return operatorName;
    }

    public void setOperatorName(String operatorName) {
        this.operatorName = operatorName;
    }

    public String[] getMenuNames() {
        return menuNames;
    }

    public void setMenuNames(String[] menuNames) {
        this.menuNames = menuNames;
    }

    public String getBusinessHours() {
        return businessHours;
    }

    public void setBusinessHours(String businessHours) {
        this.businessHours = businessHours;
    }

    public String getLatitude() {
        return latitude;
    }

    public void setLatitude(String latitude) {
        this.latitude = latitude;
    }

    public String getLongitude() {
        return longitude;
    }

    public void setLongitude(String longitude) {
        this.longitude = longitude;
    }
}
