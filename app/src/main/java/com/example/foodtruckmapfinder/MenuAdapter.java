package com.example.foodtruckmapfinder;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;
import java.util.List;

public class MenuAdapter extends ArrayAdapter<MenuItem> {
    public MenuAdapter(Context context, List<MenuItem> menuItems) {
        super(context, 0, menuItems);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        MenuItem menuItem = getItem(position);
        if (convertView == null) {
            convertView = LayoutInflater.from(getContext()).inflate(android.R.layout.simple_list_item_2, parent, false);
        }

        TextView title = convertView.findViewById(android.R.id.text1);
        TextView subtitle = convertView.findViewById(android.R.id.text2);

        title.setText(menuItem.getName());
        subtitle.setText(menuItem.getDescription() + " - RM " + menuItem.getPrice());

        return convertView;
    }
}
