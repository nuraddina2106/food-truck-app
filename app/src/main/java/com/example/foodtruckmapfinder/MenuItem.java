package com.example.foodtruckmapfinder;

public class MenuItem {
    private String name;
    private String description;
    private double price;
    private String image; // String field for storing the image URL

    public MenuItem(String name, String description, double price, String image) {
        this.name = name;
        this.description = description;
        this.price = price;
        this.image = image;
    }

    public String getName() {
        return name;
    }

    public String getDescription() {
        return description;
    }

    public double getPrice() {
        return price;
    }

    public String getImage() {
        return image;
    }
}
