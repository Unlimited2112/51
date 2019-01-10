function isInteger = isInteger(value)
    piece = abs(value - round(value));
    isInteger = (piece < 0.0000001);
end

